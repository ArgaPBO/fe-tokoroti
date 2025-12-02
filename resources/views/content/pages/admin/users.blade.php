@extends('layouts.contentNavbarLayout')

@section('title', 'Users')

@section('content')

<div class="card">
  <div class="card-header d-flex flex-column flex-md-row align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2 w-100">
      <div class="me-2">
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addUserModal" id="openAddUserBtn">
          <i class="ri-add-line me-1"></i> Add User
        </button>
      </div>
      <div class="d-flex align-items-center ms-auto gap-2 w-100">
        <input type="text" class="form-control" id="searchUserInput" placeholder="Search by name or username..." />
        <select id="filterRole" class="form-select" style="max-width: 220px;">
          <option value="">All Roles</option>
          <option value="admin">Admin</option>
          <option value="branch">Branch</option>
        </select>
      </div>
    </div>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Name</th>
          <th>Username</th>
          <th>Role / Branch</th>
          <th>Created</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="usersTableBody" class="table-border-bottom-0">
        {{-- Filled by JS --}}
      </tbody>
    </table>
  </div>

  <div class="card-footer d-flex justify-content-center">
    <nav aria-label="Page navigation">
      <ul class="pagination mb-0" id="usersPagination"></ul>
    </nav>
  </div>
</div>

<!-- Add / Edit User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="userForm">
          <input type="hidden" id="editingUserId" value="">

          <div class="mb-3">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" id="userName" class="form-control" required />
          </div>

          <div class="mb-3">
            <label class="form-label">Username <span class="text-danger">*</span></label>
            <input type="text" id="userUsername" class="form-control" required />
          </div>

          <div class="mb-3">
            <label class="form-label">Password <span id="passwordHint" class="text-muted">(required for new user, optional for edit)</span></label>
            <input type="password" id="userPassword" class="form-control" minlength="8" />
          </div>

          <div class="mb-3" id="branchSelectContainer">
            <label class="form-label">Role / Branch</label>
            <select id="branchSelect" class="form-select">
              <option value="">Admin</option>
            </select>
            <div class="form-text">Choose "Admin" to create an admin, or select a branch to create branch user.</div>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="submitUserBtn">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Delete confirmation -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteUserModalLabel">Delete User?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this user? This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteUserBtn">Delete</button>
      </div>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
const API_URL = '{{ env("API_URL") }}';
let currentUsersPage = 1;
let usersToDelete = null;
let branchesCache = [];

function getCookie(name) {
  const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
  return match ? decodeURIComponent(match[2]) : null;
}

function authHeaders() {
  const headers = { 'Content-Type': 'application/json' };
  const token = getCookie('token');
  if (token) headers['Authorization'] = `Bearer ${token}`;
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
  if (csrf) headers['X-CSRF-TOKEN'] = csrf;
  return headers;
}

function showAlert(message, type = 'info') {
  const alertHtml = `
    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
      ${message}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  `;

  const container = document.createElement('div');
  container.innerHTML = alertHtml;

  const layoutPage = document.querySelector('.layout-page');
  if (layoutPage) {
    const navbar = layoutPage.querySelector('.layout-navbar');
    if (navbar && navbar.nextSibling) {
      layoutPage.insertBefore(container.firstElementChild, navbar.nextSibling);
    } else {
      layoutPage.appendChild(container.firstElementChild);
    }
  } else {
    document.body.appendChild(container.firstElementChild);
  }
}

async function fetchBranches() {
  try {
    const res = await fetch(`${API_URL}/branchesall`, { headers: authHeaders(), credentials: 'include' });
    if (!res.ok) throw new Error('Failed to fetch branches');
    const data = await res.json();
    branchesCache = data.data || data; // support different response shapes

    const select = document.getElementById('branchSelect');
    // Clear but keep Admin option
    select.innerHTML = '<option value="">Admin</option>';
    branchesCache.forEach(b => {
      const opt = document.createElement('option');
      opt.value = b.id;
      opt.textContent = b.name;
      select.appendChild(opt);
    });
  } catch (err) {
    console.error(err);
    showAlert('Failed to load branches', 'warning');
  }
}

async function fetchUsers(page = 1) {
  try {
    const search = document.getElementById('searchUserInput').value.trim();
    const role = document.getElementById('filterRole').value;
    let url = `${API_URL}/users?page=${page}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (role) url += `&role=${encodeURIComponent(role)}`;

    const res = await fetch(url, { headers: authHeaders(), credentials: 'include' });
    if (!res.ok) throw new Error('Failed to fetch users');
    const data = await res.json();

    renderUsersTable(data.data);
    renderUsersPagination(data);
    currentUsersPage = page;
  } catch (err) {
    console.error(err);
    showAlert('Failed to load users', 'danger');
  }
}

function renderUsersTable(users) {
  const tbody = document.getElementById('usersTableBody');
  tbody.innerHTML = '';
  if (!users || users.length === 0) {
    tbody.innerHTML = '<tr><td colspan="5" class="text-center">No users found</td></tr>';
    return;
  }

  users.forEach(u => {
    const row = document.createElement('tr');
    const created = u.created_at ? new Date(u.created_at).toLocaleDateString('id-ID') : '-';
    const branchLabel = u.branch ? u.branch.name : 'Admin';

    row.innerHTML = `
      <td><strong>${escapeHtml(u.name)}</strong></td>
      <td>${escapeHtml(u.username)}</td>
      <td>${escapeHtml(branchLabel)}</td>
      <td>${created}</td>
      <td>
        <div class="d-flex gap-2">
          <button class="btn btn-sm btn-outline-primary" onclick="openEditUser(${u.id})"><i class="ri-edit-2-line"></i></button>
          <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal" onclick="prepareDeleteUser(${u.id})"><i class="ri-delete-bin-line"></i></button>
        </div>
      </td>
    `;

    tbody.appendChild(row);
  });
}

function renderUsersPagination(data) {
  const ul = document.getElementById('usersPagination');
  ul.innerHTML = '';

  const prevLi = document.createElement('li');
  prevLi.className = `page-item ${data.current_page === 1 ? 'disabled' : ''}`;
  prevLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchUsers(${data.current_page - 1})"><i class="ri-arrow-left-s-line"></i></a>`;
  ul.appendChild(prevLi);

  for (let i = 1; i <= data.last_page; i++) {
    const li = document.createElement('li');
    li.className = `page-item ${i === data.current_page ? 'active' : ''}`;
    li.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchUsers(${i})">${i}</a>`;
    ul.appendChild(li);
  }

  const nextLi = document.createElement('li');
  nextLi.className = `page-item ${data.current_page === data.last_page ? 'disabled' : ''}`;
  nextLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchUsers(${data.current_page + 1})"><i class="ri-arrow-right-s-line"></i></a>`;
  ul.appendChild(nextLi);
}

function escapeHtml(unsafe) {
  if (unsafe === null || unsafe === undefined) return '';
  return String(unsafe)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

// Create or update user
async function submitUser() {
  const editingId = document.getElementById('editingUserId').value || null;
  const name = document.getElementById('userName').value.trim();
  const username = document.getElementById('userUsername').value.trim();
  const password = document.getElementById('userPassword').value;

  if (!name || !username || (editingId === null && !password)) {
    showAlert('Please fill required fields. Password required for new user.', 'warning');
    return;
  }

  try {
    const payload = { name, username };
    // On create, include branch_id (can be empty string -> null)
    if (!editingId) {
      const branchVal = document.getElementById('branchSelect').value;
      payload.branch_id = branchVal === '' ? null : parseInt(branchVal);
      payload.password = password;
    } else {
      // update: password is optional
      if (password) payload.password = password;
    }

    const method = editingId ? 'PUT' : 'POST';
    const url = editingId ? `${API_URL}/users/${editingId}` : `${API_URL}/users`;

    const res = await fetch(url, {
      method,
      headers: authHeaders(),
      credentials: 'include',
      body: JSON.stringify(payload)
    });

    const result = await res.json();

    if (res.ok) {
      const verb = editingId ? 'updated' : 'created';
      showAlert(`✓ User ${verb} successfully`, 'success');
      bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
      // reset form
      document.getElementById('userForm').reset();
      document.getElementById('editingUserId').value = '';
      fetchUsers(1);
    } else {
      // show validation errors
      if (result.errors) {
        const errors = Object.values(result.errors).flat().join('<br/>');
        showAlert(errors, 'warning');
      } else if (result.message) {
        showAlert(result.message, 'warning');
      } else {
        showAlert('Failed to save user', 'danger');
      }
    }
  } catch (err) {
    console.error(err);
    showAlert('Failed to save user', 'danger');
  }
}

// Open edit modal but do not allow changing branch (server disallows branch update)
async function openEditUser(id) {
  try {
    const res = await fetch(`${API_URL}/users/${id}`, { headers: authHeaders(), credentials: 'include' });
    if (!res.ok) throw new Error('Failed to fetch user');
    const user = await res.json();

    document.getElementById('editingUserId').value = user.id;
    document.getElementById('userName').value = user.name || '';
    document.getElementById('userUsername').value = user.username || '';
    document.getElementById('userPassword').value = '';

    // Hide branch select on edit because API prevents changing branch via update
    document.getElementById('branchSelectContainer').style.display = 'none';
    document.getElementById('addUserModalLabel').textContent = 'Edit User';
    const modal = new bootstrap.Modal(document.getElementById('addUserModal'));
    modal.show();
  } catch (err) {
    console.error(err);
    showAlert('Failed to load user data', 'danger');
  }
}

function prepareDeleteUser(id) {
  usersToDelete = id;
}

async function confirmDeleteUser() {
  if (!usersToDelete) return;
  try {
    const res = await fetch(`${API_URL}/users/${usersToDelete}`, { method: 'DELETE', headers: authHeaders(), credentials: 'include' });
    const body = await res.json();
    if (res.ok) {
      showAlert('✓ User deleted', 'success');
      bootstrap.Modal.getInstance(document.getElementById('deleteUserModal')).hide();
      fetchUsers(currentUsersPage);
    } else {
      showAlert(body.message || 'Failed to delete user', 'warning');
    }
  } catch (err) {
    console.error(err);
    showAlert('Failed to delete user', 'danger');
  }
}

// Reset modal state when it's hidden
document.getElementById('addUserModal').addEventListener('hidden.bs.modal', () => {
  document.getElementById('userForm').reset();
  document.getElementById('editingUserId').value = '';
  document.getElementById('branchSelectContainer').style.display = 'block';
  document.getElementById('addUserModalLabel').textContent = 'Add User';
});

// Event listeners
document.getElementById('submitUserBtn').addEventListener('click', submitUser);
document.getElementById('confirmDeleteUserBtn').addEventListener('click', confirmDeleteUser);

// search and role filter
let searchTimeout = null;
document.getElementById('searchUserInput').addEventListener('input', (e) => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => fetchUsers(1), 400);
});
document.getElementById('filterRole').addEventListener('change', () => fetchUsers(1));

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  fetchBranches();
  fetchUsers(1);
});
</script>

@endsection

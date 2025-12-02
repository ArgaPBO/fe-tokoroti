@extends('layouts.contentNavbarLayout')

{{-- Judul ini akan muncul di navbar atas Anda --}}
@section('title', 'Management Branch')

@section('content')

  <div class="card">

    <!-- ===== Tombol Aksi (Action Bar) ===== -->
    <div class="card-header d-flex flex-column flex-md-row align-items-center justify-content-between">

      {{-- Search and filter --}}
      <div>
        <input type="text" id="searchBranch" class="form-control" placeholder="Search branch name..." />
      </div>

      {{-- Tombol Sortir dan Tambah Produk --}}
      <div class="d-flex">
        {{-- Tombol Pemicu Modal "Add Branch" --}}
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addBranchModal">
          <i class="ri-add-line me-1"></i> Add Branch
        </button>
      </div>

    </div>

    <!-- ===== Tabel Branch ===== -->
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0" id="branchTableBody">
          {{-- Data akan diisi oleh JavaScript --}}
        </tbody>
      </table>
    </div>

    <!-- ===== Paginasi ===== -->
    <div class="card-footer d-flex justify-content-center">
      <nav aria-label="Page navigation">
        <ul class="pagination mb-0" id="paginationList">
          {{-- Pagination akan diisi oleh JavaScript --}}
        </ul>
      </nav>
    </div>

  </div>


  <!-- ===== MODALS ===== -->

  <!-- 1. Modal Tambah Branch (Add Branch) -->
  <div class="modal fade" id="addBranchModal" tabindex="-1" aria-labelledby="addBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addBranchModalLabel">Add New Branch</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addBranchForm">
            <div class="mb-3">
              <label for="addBranchName" class="form-label">Branch Name</label>
              <input type="text" class="form-control" id="addBranchName" placeholder="e.g., Branch Wonosari" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="saveBranchBtn">Save Branch</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Modal Edit Branch (Edit Branch) -->
  <div class="modal fade" id="editBranchModal" tabindex="-1" aria-labelledby="editBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editBranchModalLabel">Edit Branch</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editBranchForm">
            <div class="mb-3">
              <label for="editBranchName" class="form-label">Branch Name</label>
              <input type="text" class="form-control" id="editBranchName" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="saveEditBranchBtn">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const API_URL = '{{ env("API_URL") }}';
    let currentPage = 1;
    let currentBranchId = null;

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
      const alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>`;
      const container = document.createElement('div');
      container.innerHTML = alertHtml;
      const layoutPage = document.querySelector('.layout-page');
      if (layoutPage) {
        layoutPage.insertBefore(container.firstChild, layoutPage.firstChild);
      } else {
        document.body.insertBefore(container.firstChild, document.body.firstChild);
      }
    }

    // Fetch branches from API
    async function fetchBranches(page = 1) {
      try {
        const search = document.getElementById('searchBranch').value.trim();
        let url = `${API_URL}/branches?page=${page}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        const response = await fetch(url, {
          headers: authHeaders()
        });
        const data = await response.json();
        
        renderBranchTable(data.data);
        renderPagination(data);
        currentPage = page;
      } catch (error) {
        console.error('Error fetching branches:', error);
        showAlert('Failed to load branches', 'danger');
      }
    }

    // Render branch table
    function renderBranchTable(branches) {
      const tbody = document.getElementById('branchTableBody');
      tbody.innerHTML = '';

      if (branches.length === 0) {
        tbody.innerHTML = '<tr><td colspan="2" class="text-center">No branches found</td></tr>';
        return;
      }

      branches.forEach(branch => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td><strong>${branch.name}</strong></td>
          <td>
            <div class="d-flex">
              <a class="btn btn-sm btn-icon btn-outline-primary me-2" href="javascript:void(0);" 
                 data-bs-toggle="modal" data-bs-target="#editBranchModal" onclick="loadEditBranch(${branch.id}, '${branch.name}')">
                <i class="ri-pencil-line"></i>
              </a>
              <a class="btn btn-sm btn-icon btn-outline-info me-2" href="/admin/branch?id=${branch.id}" title="View branch details">
                <i class="ri-eye-line"></i>
              </a>
              <button class="btn btn-sm btn-icon btn-outline-danger" onclick="confirmDeleteBranch(${branch.id}, '${branch.name.replace(/'/g, "\\'")}')" title="Delete branch">
                <i class="ri-delete-bin-5-line"></i>
              </button>
            </div>
          </td>
        `;
        tbody.appendChild(row);
      });
    }

    // Render pagination
    function renderPagination(data) {
      const paginationList = document.getElementById('paginationList');
      paginationList.innerHTML = '';

      // Previous button
      const prevLi = document.createElement('li');
      prevLi.className = `page-item ${data.current_page === 1 ? 'disabled' : ''}`;
      prevLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchBranches(${data.current_page - 1})"><i class="ri-arrow-left-s-line"></i></a>`;
      paginationList.appendChild(prevLi);

      // Page numbers
      for (let i = 1; i <= data.last_page; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === data.current_page ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchBranches(${i})">${i}</a>`;
        paginationList.appendChild(li);
      }

      // Next button
      const nextLi = document.createElement('li');
      nextLi.className = `page-item ${data.current_page === data.last_page ? 'disabled' : ''}`;
      nextLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchBranches(${data.current_page + 1})"><i class="ri-arrow-right-s-line"></i></a>`;
      paginationList.appendChild(nextLi);
    }

    // Load edit branch data
    function loadEditBranch(id, name) {
      currentBranchId = id;
      document.getElementById('editBranchName').value = name;
    }

    // Save new branch
    document.getElementById('saveBranchBtn').addEventListener('click', async function() {
      const name = document.getElementById('addBranchName').value.trim();
      
      if (!name) {
        showAlert('Please enter a branch name', 'warning');
        return;
      }

      try {
        const response = await fetch(`${API_URL}/branches`, {
          method: 'POST',
          headers: authHeaders(),
          body: JSON.stringify({ name })
        });

        const data = await response.json();
        
        if (response.ok) {
          showAlert(data.message || 'Branch created successfully', 'success');
          document.getElementById('addBranchForm').reset();
          bootstrap.Modal.getInstance(document.getElementById('addBranchModal')).hide();
          fetchBranches(1);
        } else {
          showAlert(data.message || 'Failed to create branch', 'danger');
        }
      } catch (error) {
        console.error('Error creating branch:', error);
        showAlert('Error creating branch', 'danger');
      }
    });

    // Save edited branch
    document.getElementById('saveEditBranchBtn').addEventListener('click', async function() {
      const name = document.getElementById('editBranchName').value.trim();
      
      if (!name) {
        showAlert('Please enter a branch name', 'warning');
        return;
      }

      try {
        const response = await fetch(`${API_URL}/branches/${currentBranchId}`, {
          method: 'PUT',
          headers: authHeaders(),
          body: JSON.stringify({ name })
        });

        const data = await response.json();
        
        if (response.ok) {
          showAlert(data.message || 'Branch updated successfully', 'success');
          bootstrap.Modal.getInstance(document.getElementById('editBranchModal')).hide();
          fetchBranches(currentPage);
        } else {
          showAlert(data.message || 'Failed to update branch', 'danger');
        }
      } catch (error) {
        console.error('Error updating branch:', error);
        showAlert('Error updating branch', 'danger');
      }
    });

    // delete branch with confirmation
    async function deleteBranch(id){
      try{
        const res = await fetch(`${API_URL}/branches/${id}`, {
          method: 'DELETE',
          headers: authHeaders(),
          credentials: 'include'
        });
        const data = await res.json();
        if(res.ok){
          showAlert(data.message || 'Branch deleted', 'success');
          fetchBranches(currentPage);
        } else {
          showAlert(data.message || 'Failed to delete branch', 'danger');
        }
      } catch(err){
        console.error(err);
        showAlert('Error deleting branch', 'danger');
      }
    }

    function confirmDeleteBranch(id, name){
      if(confirm(`Delete branch ${name}? This action cannot be undone.`)){
        deleteBranch(id);
      }
    }

    // Load branches on page load
    document.addEventListener('DOMContentLoaded', function() {
      fetchBranches(1);
      
      // Wire search input
      let searchTimeout = null;
      document.getElementById('searchBranch').addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => fetchBranches(1), 400);
      });
    });
  </script>

@endsection

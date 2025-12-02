@extends('layouts.contentNavbarLayout')

{{-- Judul ini akan muncul di navbar atas Anda --}}
@section('title', 'Management Branch')

@section('content')

  <div class="card">

    <!-- ===== Tombol Aksi (Action Bar) ===== -->
    <div class="card-header d-flex flex-column flex-md-row align-items-center justify-content-between">

      {{-- Dibiarkan kosong di kiri agar tombol "Add Branch" di kanan --}}
      <div></div>

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
      // include CSRF only when blade provides it
      const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
      if (csrf) headers['X-CSRF-TOKEN'] = csrf;
      return headers;
    }

    // Fetch branches from API
    async function fetchBranches(page = 1) {
      try {
        const response = await fetch(`${API_URL}/branches?page=${page}`, {
          headers: authHeaders()
        });
        const data = await response.json();
        
        renderBranchTable(data.data);
        renderPagination(data);
        currentPage = page;
      } catch (error) {
        console.error('Error fetching branches:', error);
        alert('Failed to load branches');
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
        alert('Please enter a branch name');
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
          alert(data.message || 'Branch created successfully');
          document.getElementById('addBranchForm').reset();
          bootstrap.Modal.getInstance(document.getElementById('addBranchModal')).hide();
          fetchBranches(1);
        } else {
          alert(data.message || 'Failed to create branch');
        }
      } catch (error) {
        console.error('Error creating branch:', error);
        alert('Error creating branch');
      }
    });

    // Save edited branch
    document.getElementById('saveEditBranchBtn').addEventListener('click', async function() {
      const name = document.getElementById('editBranchName').value.trim();
      
      if (!name) {
        alert('Please enter a branch name');
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
          alert(data.message || 'Branch updated successfully');
          bootstrap.Modal.getInstance(document.getElementById('editBranchModal')).hide();
          fetchBranches(currentPage);
        } else {
          alert(data.message || 'Failed to update branch');
        }
      } catch (error) {
        console.error('Error updating branch:', error);
        alert('Error updating branch');
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
          alert(data.message || 'Branch deleted');
          fetchBranches(currentPage);
        } else {
          alert(data.message || 'Failed to delete branch');
        }
      } catch(err){
        console.error(err); alert('Error deleting branch');
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
    });
  </script>

@endsection

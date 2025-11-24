@extends('layouts.contentNavbarLayout')

{{-- Judul ini akan muncul di navbar atas Anda --}}
@section('title', 'Branch Product Management')

@section('content')

  <div class="card">

    <!-- ===== Tombol Aksi (Action Bar) ===== -->
    <div class="card-header d-flex flex-column flex-md-row align-items-center justify-content-between">
      {{-- Tombol Pemicu Modal "Add Product" --}}
      <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addBranchProductModal">
        <i class="ri-add-line me-1"></i> Add Branch Product
      </button>
    </div>

    <!-- ===== Tabel Branch Product ===== -->
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Product Name</th>
            <th>Branch Price</th>
            <th>Base Price</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0" id="branchProductTableBody">
          {{-- Data akan diisi oleh JavaScript --}}
        </tbody>
      </table>
    </div>

    <!-- ===== Paginasi ===== -->
    <div class="card-footer d-flex justify-content-center">
      <nav aria-label="Page navigation">
        <ul class="pagination mb-0" id="branchProductPagination">
          {{-- Pagination diisi oleh JS --}}
        </ul>
      </nav>
    </div>

  </div>


  <!-- ===== MODALS ===== -->

  <!-- 1. Modal Tambah Branch Product (Add Branch Product) -->
  <div class="modal fade" id="addBranchProductModal" tabindex="-1" aria-labelledby="addBranchProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addBranchProductModalLabel">Add Product to Branch</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addBranchProductForm">
            <div class="mb-3">
              <label for="addProductSearch" class="form-label">Search Product</label>
              <input type="text" class="form-control" id="addProductSearch" placeholder="Search products...">
            </div>

            <div class="mb-3">
              <label for="addProductSelect" class="form-label">Select Product</label>
              <select class="form-control" id="addProductSelect" required>
                <option value="">Choose a product...</option>
              </select>
              <div id="productListDropdown" class="mt-2" style="max-height: 250px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px; display: none;">
                <!-- Product list will be populated here -->
              </div>
              <small class="text-muted d-block mt-2">Base Price: <span id="addProductBasePrice">-</span></small>
            </div>

            <div class="mb-3">
              <label for="addBranchPrice" class="form-label">Branch Price</label>
              <input type="number" class="form-control" id="addBranchPrice" placeholder="e.g., 25000" required>
            </div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="saveBranchProductBtn">Save</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Modal Edit Branch Product (Edit Branch Product) -->
  <div class="modal fade" id="editBranchProductModal" tabindex="-1" aria-labelledby="editBranchProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editBranchProductModalLabel">Edit Branch Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editBranchProductForm">
            <input type="hidden" id="editProductId">
            <div class="mb-3">
              <label for="editProductName" class="form-label">Product Name</label>
              <input type="text" class="form-control" id="editProductName" disabled>
            </div>

            <div class="mb-3">
              <label for="editProductBasePrice" class="form-label">Base Price</label>
              <input type="number" class="form-control" id="editProductBasePrice" disabled>
            </div>

            <div class="mb-3">
              <label for="editBranchPrice" class="form-label">Branch Price</label>
              <input type="number" class="form-control" id="editBranchPrice" placeholder="e.g., 25000" required>
            </div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="saveEditBranchProductBtn">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Modal Hapus Branch Product (Delete Branch Product) -->
  <div class="modal fade" id="deleteBranchProductModal" tabindex="-1" aria-labelledby="deleteBranchProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteBranchProductModalLabel">Remove Product from Branch?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to remove this product from the branch?</p>
          <p>This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBranchProductBtn">Remove</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const API_URL = '{{ env("API_URL") }}';
    let currentPage = 1;
    let productToDelete = null;
    let selectedProductId = null;
    let productsCache = [];

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

    async function fetchBranchProducts(page = 1) {
      try {
        const res = await fetch(`${API_URL}/branch/products?page=${page}`, { headers: authHeaders() });
        const data = await res.json();
        renderBranchProductTable(data.data);
        renderPagination(data);
        currentPage = page;
      } catch (err) {
        console.error('Error fetching branch products', err);
        alert('Failed to load branch products');
      }
    }

    function renderBranchProductTable(products) {
      const tbody = document.getElementById('branchProductTableBody');
      tbody.innerHTML = '';
      if (!products || products.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center">No branch products found</td></tr>';
        return;
      }
      products.forEach(bp => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td><strong>${bp.product?.name || 'Unknown'}</strong></td>
          <td>${Number(bp.branch_price || 0).toLocaleString()}</td>
          <td>${Number(bp.product?.price || 0).toLocaleString()}</td>
          <td>
            <div class="d-flex">
              <a class="btn btn-sm btn-icon btn-outline-primary me-2" href="javascript:void(0);" 
                 data-bs-toggle="modal" data-bs-target="#editBranchProductModal" onclick="loadEditBranchProduct(${bp.id})">
                <i class="ri-pencil-line"></i>
              </a>
              <a class="btn btn-sm btn-icon btn-outline-danger" href="javascript:void(0);" 
                 data-bs-toggle="modal" data-bs-target="#deleteBranchProductModal" onclick="prepareDeleteBranchProduct(${bp.id})">
                <i class="ri-delete-bin-line"></i>
              </a>
            </div>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    function renderPagination(data) {
      const ul = document.getElementById('branchProductPagination');
      ul.innerHTML = '';

      const prevLi = document.createElement('li');
      prevLi.className = `page-item ${data.current_page === 1 ? 'disabled' : ''}`;
      prevLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchBranchProducts(${data.current_page - 1})"><i class="ri-arrow-left-s-line"></i></a>`;
      ul.appendChild(prevLi);

      for (let i = 1; i <= data.last_page; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === data.current_page ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchBranchProducts(${i})">${i}</a>`;
        ul.appendChild(li);
      }

      const nextLi = document.createElement('li');
      nextLi.className = `page-item ${data.current_page === data.last_page ? 'disabled' : ''}`;
      nextLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchBranchProducts(${data.current_page + 1})"><i class="ri-arrow-right-s-line"></i></a>`;
      ul.appendChild(nextLi);
    }

    // Fetch products for dropdown in add modal
    async function fetchProductsForSelection(search = '') {
      try {
        const url = search 
          ? `${API_URL}/products?search=${encodeURIComponent(search)}` 
          : `${API_URL}/products`;
          console.log('Fetching products from URL:', url);
        const res = await fetch(url, { headers: authHeaders() });
        const data = await res.json();
        productsCache = data.data || [];
        renderProductDropdown(data.data);
      } catch (err) {
        console.error('Error fetching products for selection', err);
      }
    }

    function renderProductDropdown(products) {
      const dropdown = document.getElementById('productListDropdown');
      dropdown.innerHTML = '';
      if (!products || products.length === 0) {
        dropdown.innerHTML = '<div class="p-2 text-muted">No products found</div>';
        return;
      }
      products.forEach(p => {
        const div = document.createElement('div');
        div.className = 'p-2 border-bottom cursor-pointer';
        div.style.cursor = 'pointer';
        div.innerHTML = `
          <div><strong>${p.name}</strong></div>
          <small class="text-muted">Base Price: ${Number(p.price).toLocaleString()}</small>
        `;
        div.onclick = () => selectProduct(p.id, p.name, p.price);
        dropdown.appendChild(div);
      });
    }

    function selectProduct(id, name, price) {
      selectedProductId = id;
      document.getElementById('addProductSearch').value = name;
      document.getElementById('addProductSelect').value = id;
      document.getElementById('addProductBasePrice').textContent = Number(price).toLocaleString();
      document.getElementById('productListDropdown').style.display = 'none';
    }

    // Load single branch product for edit
    async function loadEditBranchProduct(id) {
      try {
        const res = await fetch(`${API_URL}/branch/products/${id}`, { headers: authHeaders() });
        const data = await res.json();
        document.getElementById('editProductId').value = data.id;
        document.getElementById('editProductName').value = data.product?.name || '';
        document.getElementById('editProductBasePrice').value = data.product?.price || 0;
        document.getElementById('editBranchPrice').value = data.branch_price || 0;
      } catch (err) {
        console.error('Error loading branch product', err);
        alert('Failed to load branch product data');
      }
    }

    // Prepare delete
    function prepareDeleteBranchProduct(id) {
      productToDelete = id;
    }

    // Handle product search in add modal
    document.getElementById('addProductSearch').addEventListener('input', (e) => {
      const search = e.target.value.trim();
      if (search.length > 0) {
        document.getElementById('productListDropdown').style.display = 'block';
        fetchProductsForSelection(search);
      } else {
        document.getElementById('productListDropdown').style.display = 'none';
      }
    });

    // Handle product select focus
    document.getElementById('addProductSelect').addEventListener('focus', () => {
      if (productsCache.length === 0) {
        document.getElementById('productListDropdown').style.display = 'block';
        fetchProductsForSelection();
      }
    });

    // Save new branch product
    document.getElementById('saveBranchProductBtn').addEventListener('click', async () => {
      const productId = selectedProductId || Number(document.getElementById('addProductSelect').value);
      const branchPrice = Number(document.getElementById('addBranchPrice').value);
      if (!productId || !branchPrice) { alert('Please select a product and enter branch price'); return; }
      try {
        const res = await fetch(`${API_URL}/branch/products`, {
          method: 'POST',
          headers: authHeaders(),
          body: JSON.stringify({ product_id: productId, branch_price: branchPrice })
        });
        const data = await res.json();
        if (res.ok) {
          alert(data.message || 'Branch product created');
          document.getElementById('addBranchProductForm').reset();
          selectedProductId = null;
          document.getElementById('productListDropdown').style.display = 'none';
          document.getElementById('addProductBasePrice').textContent = '-';
          bootstrap.Modal.getInstance(document.getElementById('addBranchProductModal')).hide();
          fetchBranchProducts(1);
        } else {
          alert(data.message || 'Failed to create branch product');
        }
      } catch (err) { console.error(err); alert('Error creating branch product'); }
    });

    // Save edited branch product
    document.getElementById('saveEditBranchProductBtn').addEventListener('click', async () => {
      const id = document.getElementById('editProductId').value;
      const branchPrice = Number(document.getElementById('editBranchPrice').value);
      if (!id || !branchPrice) { alert('Please enter branch price'); return; }
      try {
        const res = await fetch(`${API_URL}/branch/products/${id}`, {
          method: 'PUT',
          headers: authHeaders(),
          body: JSON.stringify({ branch_price: branchPrice })
        });
        const data = await res.json();
        if (res.ok) {
          alert(data.message || 'Branch product updated');
          bootstrap.Modal.getInstance(document.getElementById('editBranchProductModal')).hide();
          fetchBranchProducts(currentPage);
        } else {
          alert(data.message || 'Failed to update branch product');
        }
      } catch (err) { console.error(err); alert('Error updating branch product'); }
    });

    // Confirm delete
    document.getElementById('confirmDeleteBranchProductBtn').addEventListener('click', async () => {
      if (!productToDelete) return;
      try {
        const res = await fetch(`${API_URL}/branch/products/${productToDelete}`, {
          method: 'DELETE',
          headers: authHeaders()
        });
        const data = await res.json();
        if (res.ok) {
          alert(data.message || 'Branch product removed');
          bootstrap.Modal.getInstance(document.getElementById('deleteBranchProductModal')).hide();
          fetchBranchProducts(currentPage);
        } else {
          alert(data.message || 'Failed to remove branch product');
        }
      } catch (err) { console.error(err); alert('Error removing branch product'); }
    });

    document.addEventListener('DOMContentLoaded', () => fetchBranchProducts(1));
  </script>
@endsection

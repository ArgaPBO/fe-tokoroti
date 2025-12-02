@extends('layouts.contentNavbarLayout')

{{-- Judul ini akan muncul di navbar atas Anda --}}
@section('title', 'Management Product')

@section('content')

  <div class="card">

    <!-- ===== Tombol Aksi (Action Bar) ===== -->
    <div class="card-header d-flex flex-column flex-md-row align-items-center justify-content-between">

      {{-- Search and filter --}}
      <div>
        <input type="text" id="searchProduct" class="form-control" placeholder="Search product name..." />
      </div>

      {{-- Tombol Tampilan Grid/List --}}
      <div class="btn-group mb-2 mb-md-0" role="group" aria-label="View Toggle">
        <button type="button" class="btn btn-outline-primary active"><i class="ri-list-check ri-20px"></i></button>
        <button type="button" class="btn btn-outline-primary"><i class="ri-layout-grid-fill ri-20px"></i></button>
      </div>

      {{-- Tombol Sortir dan Tambah Produk --}}
      <div class="d-flex">
        <div class="dropdown me-2">
          <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownSort"
            data-bs-toggle="dropdown" aria-expanded="false">
            <i class="ri-filter-3-line me-1"></i> Sort
          </button>
          <ul class="dropdown-menu" aria-labelledby="dropdownSort">
            <li><a class="dropdown-item" href="javascript:void(0);">Name (A-Z)</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">Price (Low to High)</a></li>
            <li><a class="dropdown-item" href="javascript:void(0);">Stock (Low to High)</a></li>
          </ul>
        </div>
        {{-- Tombol Pemicu Modal "Add Product" --}}
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addProductModal">
          <i class="ri-add-line me-1"></i> Add Product
        </button>
      </div>

    </div>

    <!-- ===== Tabel Produk ===== -->
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0" id="productTableBody">
          {{-- Data akan diisi oleh JavaScript --}}
        </tbody>
      </table>
    </div>

    <!-- ===== Paginasi ===== -->
    <div class="card-footer d-flex justify-content-center">
      <nav aria-label="Page navigation">
        <ul class="pagination mb-0" id="productPagination">
          {{-- Pagination diisi oleh JS --}}
        </ul>
      </nav>
    </div>

  </div>


  <!-- ===== MODALS ===== -->

  <!-- 1. Modal Tambah Produk (Add Product) -->
  <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addProductForm">
            <div class="mb-3">
              <label for="addProductName" class="form-label">Product Name</label>
              <input type="text" class="form-control" id="addProductName" placeholder="e.g., Chiffon Uk 20" required>
            </div>

            <div class="mb-3">
              <label for="addProductPrice" class="form-label">Price</label>
              <input type="number" class="form-control" id="addProductPrice" placeholder="e.g., 20000" required>
            </div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="saveProductBtn">Save Product</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Modal Edit Produk (Edit Product) -->
  <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editProductForm">
            <input type="hidden" id="editProductId">
            <div class="mb-3">
              <label for="editProductName" class="form-label">Product Name</label>
              <input type="text" class="form-control" id="editProductName" placeholder="e.g., Chiffon Uk 20" required>
            </div>

            <div class="mb-3">
              <label for="editProductPrice" class="form-label">Price</label>
              <input type="number" class="form-control" id="editProductPrice" placeholder="e.g., 20000" required>
            </div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="saveEditProductBtn">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Modal Hapus Produk (Delete Product) -->
  <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteProductModalLabel">Delete Product?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this product?</p>
          <p>This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteProductBtn">Delete</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    const API_URL = '{{ env("API_URL") }}';
    let currentPage = 1;
    let productToDelete = null;

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

    function showAlert(message, type = 'success') {
      const alertDiv = document.createElement('div');
      alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
      alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      `;
      const container = document.querySelector('.layout-page') || document.body;
      container.insertBefore(alertDiv, container.firstChild);
      setTimeout(() => alertDiv.remove(), 5000);
    }

    let searchTimeout;

    async function fetchProducts(page = 1) {
      try {
        const search = document.getElementById('searchProduct').value.trim();
        let url = `${API_URL}/products?page=${page}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        const res = await fetch(url, { headers: authHeaders() });
        const data = await res.json();
        renderProductTable(data.data);
        renderPagination(data);
        currentPage = page;
      } catch (err) {
        console.error('Error fetching products', err);
        showAlert('Failed to load products', 'danger');
      }
    }

    function renderProductTable(products) {
      const tbody = document.getElementById('productTableBody');
      tbody.innerHTML = '';
      if (!products || products.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center">No products found</td></tr>';
        return;
      }
      products.forEach(p => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td><strong>${p.name}</strong></td>
          <td>${Number(p.price).toLocaleString()}</td>
          <td>
            <div class="d-flex">
              <a class="btn btn-sm btn-icon btn-outline-primary me-2" href="javascript:void(0);" 
                 data-bs-toggle="modal" data-bs-target="#editProductModal" onclick="loadEditProduct(${p.id})">
                <i class="ri-pencil-line"></i>
              </a>
              <a class="btn btn-sm btn-icon btn-outline-danger" href="javascript:void(0);" 
                 data-bs-toggle="modal" data-bs-target="#deleteProductModal" onclick="prepareDeleteProduct(${p.id})">
                <i class="ri-delete-bin-line"></i>
              </a>
            </div>
          </td>
        `;
        tbody.appendChild(tr);
      });
    }

    function renderPagination(data) {
      const ul = document.getElementById('productPagination');
      ul.innerHTML = '';

      const prevLi = document.createElement('li');
      prevLi.className = `page-item ${data.current_page === 1 ? 'disabled' : ''}`;
      prevLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchProducts(${data.current_page - 1})"><i class="ri-arrow-left-s-line"></i></a>`;
      ul.appendChild(prevLi);

      for (let i = 1; i <= data.last_page; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === data.current_page ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchProducts(${i})">${i}</a>`;
        ul.appendChild(li);
      }

      const nextLi = document.createElement('li');
      nextLi.className = `page-item ${data.current_page === data.last_page ? 'disabled' : ''}`;
      nextLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchProducts(${data.current_page + 1})"><i class="ri-arrow-right-s-line"></i></a>`;
      ul.appendChild(nextLi);
    }

    // Load single product into edit modal
    async function loadEditProduct(id) {
      try {
        const res = await fetch(`${API_URL}/products/${id}`, { headers: authHeaders() });
        const data = await res.json();
        document.getElementById('editProductId').value = data.id;
        document.getElementById('editProductName').value = data.name;
        document.getElementById('editProductPrice').value = data.price;
      } catch (err) {
        console.error('Error loading product', err);
        showAlert('Failed to load product data', 'danger');
      }
    }

    // Prepare delete
    function prepareDeleteProduct(id) {
      productToDelete = id;
    }

    // Save new product
    document.getElementById('saveProductBtn').addEventListener('click', async () => {
      const name = document.getElementById('addProductName').value.trim();
      const price = Number(document.getElementById('addProductPrice').value);
      if (!name || !price) { showAlert('Please enter name and price', 'warning'); return; }
      try {
        const res = await fetch(`${API_URL}/products`, {
          method: 'POST',
          headers: authHeaders(),
          body: JSON.stringify({ name, price })
        });
        const data = await res.json();
        if (res.ok) {
          showAlert(data.message || 'Product created', 'success');
          document.getElementById('addProductForm').reset();
          bootstrap.Modal.getInstance(document.getElementById('addProductModal')).hide();
          fetchProducts(1);
        } else {
          showAlert(data.message || 'Failed to create product', 'danger');
        }
      } catch (err) { console.error(err); showAlert('Error creating product', 'danger'); }
    });

    // Save edited product
    document.getElementById('saveEditProductBtn').addEventListener('click', async () => {
      const id = document.getElementById('editProductId').value;
      const name = document.getElementById('editProductName').value.trim();
      const price = Number(document.getElementById('editProductPrice').value);
      if (!id || !name || !price) { showAlert('Please enter name and price', 'warning'); return; }
      try {
        const res = await fetch(`${API_URL}/products/${id}`, {
          method: 'PUT',
          headers: authHeaders(),
          body: JSON.stringify({ name, price })
        });
        const data = await res.json();
        if (res.ok) {
          showAlert(data.message || 'Product updated', 'success');
          bootstrap.Modal.getInstance(document.getElementById('editProductModal')).hide();
          fetchProducts(currentPage);
        } else {
          showAlert(data.message || 'Failed to update product', 'danger');
        }
      } catch (err) { console.error(err); showAlert('Error updating product', 'danger'); }
    });

    // Confirm delete
    document.getElementById('confirmDeleteProductBtn').addEventListener('click', async () => {
      if (!productToDelete) return;
      try {
        const res = await fetch(`${API_URL}/products/${productToDelete}`, {
          method: 'DELETE',
          headers: authHeaders()
        });
        const data = await res.json();
        if (res.ok) {
          showAlert(data.message || 'Product deleted', 'success');
          bootstrap.Modal.getInstance(document.getElementById('deleteProductModal')).hide();
          fetchProducts(currentPage);
        } else {
          showAlert(data.message || 'Failed to delete product', 'danger');
        }
      } catch (err) { console.error(err); showAlert('Error deleting product', 'danger'); }
    });

    document.addEventListener('DOMContentLoaded', () => {
      fetchProducts(1);
      document.getElementById('searchProduct').addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => fetchProducts(1), 400);
      });
    });
  </script>
@endsection

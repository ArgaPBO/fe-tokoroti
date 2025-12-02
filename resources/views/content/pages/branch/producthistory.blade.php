@extends('layouts.contentNavbarLayoutBranch')

@section('title', 'Product History')

@section('content')

  <div class="card">

    <!-- ===== Action Bar ===== -->
    <div class="card-header d-flex flex-column flex-md-row align-items-center justify-content-between">
      <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addHistoryModal">
        <i class="ri-add-line me-1"></i> Add Single
      </button>
      <button class="btn btn-info" type="button" data-bs-toggle="modal" data-bs-target="#bulkImportModal">
        <i class="ri-upload-cloud-line me-1"></i> Bulk Import
      </button>
    </div>

    <!-- ===== Filter Bar ===== -->
    <div class="card-body pb-0">
      <div class="row g-3">
        <div class="col-md-3">
          <label for="startDate" class="form-label">Start Date</label>
          <input type="date" class="form-control" id="startDate">
        </div>
        <div class="col-md-3">
          <label for="endDate" class="form-label">End Date</label>
          <input type="date" class="form-control" id="endDate">
        </div>
        <div class="col-md-6">
          <label for="searchHistory" class="form-label">Search Product</label>
          <input type="text" class="form-control" id="searchHistory" placeholder="Search product name...">
        </div>
      </div>
    </div>

    <!-- ===== History Table ===== -->
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Produk</th>
            <th>Harga Produk</th>
            <th>Jumlah</th>
            <th>Diskon</th>
            <th>Harga Total</th>
            <th>Jenis Transaksi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0" id="historiesTableBody">
          {{-- Data akan diisi oleh JavaScript --}}
        </tbody>
      </table>
    </div>

    <!-- ===== Pagination ===== -->
    <div class="card-footer d-flex justify-content-center">
      <nav aria-label="Page navigation">
        <ul class="pagination mb-0" id="historiesPagination">
          {{-- Pagination diisi oleh JS --}}
        </ul>
      </nav>
    </div>

  </div>

  <!-- ===== MODALS ===== -->

  <!-- 1. Modal Add Single History -->
  <div class="modal fade" id="addHistoryModal" tabindex="-1" aria-labelledby="addHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addHistoryModalLabel">Add Product Transaction</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addHistoryForm">
            <div class="mb-3">
              <label for="singleDate" class="form-label">Date <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="singleDate" required>
            </div>
            <div class="mb-3">
              {{-- <label for="singleProductSearch" class="form-label">Product Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="singleProductSearch" placeholder="Search products...">
            </div>
            <div class="mb-3"> --}}
  <label for="singleProductSearch" class="form-label">Product Name <span class="text-danger">*</span></label>
  <input type="text" class="form-control" id="singleProductSearch" placeholder="Search products...">

  <div id="singleProductDropdown"
       class="mt-2"
       style="max-height:250px; overflow-y:auto; border:1px solid #ddd; border-radius:4px; display:none;"></div>
</div>

            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="singleQty" class="form-label">Quantity <span class="text-danger">*</span></label>
                  <input type="number" class="form-control" id="singleQty" placeholder="e.g., 100" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="singleType" class="form-label">Type <span class="text-danger">*</span></label>
                  <select class="form-select" id="singleType" required>
                    <option value="">Choose...</option>
                    <option value="pesanan">Pesanan</option>
                    <option value="retail">Retail</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="singleDiscPercent" class="form-label">Discount %</label>
                  <input type="number" class="form-control" id="singleDiscPercent" placeholder="e.g., 10" step="0.01">
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="singleDiscPrice" class="form-label">Discount Rp</label>
                  <input type="number" class="form-control" id="singleDiscPrice" placeholder="e.g., 5000">
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="submitSingleBtn">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Modal Bulk Import -->
  <div class="modal fade" id="bulkImportModal" tabindex="-1" aria-labelledby="bulkImportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="bulkImportModalLabel">Bulk Import Product History</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="bulkFile" class="form-label">Choose Excel File <span class="text-danger">*</span></label>
            <input type="file" class="form-control" id="bulkFile" accept=".xlsx,.xls">
            <small class="text-muted d-block mt-2">Columns: Tanggal, Produk, Jumlah, Jenis Penjualan, Diskon (Persen), Diskon (Nominal)</small>
          </div>
          {{-- <button type="button" class="btn btn-warning mb-3" id="previewBulkBtn">
            <i class="ri-eye-line me-1"></i> Preview Data
          </button> --}}

          <!-- Preview Table -->
          <div id="bulkPreviewContainer" style="display: none;">
            <h6>Preview (will import <strong id="previewRowCount">0</strong> rows)</h6>
            <div class="table-responsive">
              <table class="table table-sm table-hover">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Type</th>
                    <th>Disc %</th>
                    <th>Disc Rp</th>
                  </tr>
                </thead>
                <tbody id="bulkPreviewBody">
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="downloadTemplateBtn">
            <i class="ri-download-line me-1"></i> Download Template
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="submitBulkBtn">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Modal Delete Confirmation -->
  <div class="modal fade" id="deleteHistoryModal" tabindex="-1" aria-labelledby="deleteHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteHistoryModalLabel">Delete Record?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this transaction history? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
        </div>
      </div>
    </div>
  </div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
  <script>
    const API_URL = '{{ env("API_URL") }}';
    let currentPage = 1;
    let bulkDataCache = [];
    let recordToDelete = null;
let selectedSingleProductId = null;

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

    function getFirstDayOfMonth() { const now = new Date(); return new Date(now.getFullYear(), now.getMonth(), 1); }
    function getLastDayOfMonth() { const now = new Date(); return new Date(now.getFullYear(), now.getMonth()+1, 0); }
    function formatForInput(d) { const y=d.getFullYear(), m=String(d.getMonth()+1).padStart(2,'0'), day=String(d.getDate()).padStart(2,'0'); return `${y}-${m}-${day}`; }

    let searchTimeout;

    function showAlert(message, type = 'info') {
      const alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>`;
      const container = document.createElement('div');
      container.innerHTML = alertHtml;
      document.body.insertBefore(container.firstElementChild, document.body.firstChild);
    }

    async function fetchHistories(page = 1) {
      try {
        let url = `${API_URL}/branch/histories/products?page=${page}`;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const search = document.getElementById('searchHistory').value.trim();
        
        if (startDate) url += `&date_from=${encodeURIComponent(startDate)}`;
        if (endDate) url += `&date_to=${encodeURIComponent(endDate)}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        
        const res = await fetch(url, { headers: authHeaders() });
        if (!res.ok) throw new Error('Failed to fetch');
        const data = await res.json();
        renderHistoriesTable(data.data);
        renderPagination(data);
        currentPage = page;
      } catch (err) {
        console.error('Error fetching histories:', err);
        showAlert('Failed to load histories', 'danger');
      }
    }

    function renderHistoriesTable(histories) {
      const tbody = document.getElementById('historiesTableBody');
      tbody.innerHTML = '';
      if (!histories || histories.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center">No records found</td></tr>';
        return;
      }
      histories.forEach(h => {
        const row = document.createElement('tr');
        const dateObj = new Date(h.date);
        const formattedDate = dateObj.toLocaleDateString('id-ID');
        let discountDisplay = h.discount_percent ? `${h.discount_percent}%` : '-';
        if (h.discount_price) {
          discountDisplay = `Rp ${parseInt(h.discount_price).toLocaleString('id-ID')}`;
        }
        let totalPrice = h.product_price ? parseInt(h.product_price) * parseInt(h.quantity) : 0;
        if (h.discount_price) {
          totalPrice -= parseInt(h.discount_price);
        } else if (h.discount_percent) {
          totalPrice -= totalPrice * (parseFloat(h.discount_percent) / 100);
        }
        const totalPriceDisplay = `Rp ${totalPrice.toLocaleString('id-ID')}`;
        // const discountPriceDisplay = h.discount_price ? `Rp ${parseInt(h.discount_price).toLocaleString('id-ID')}` : '-';
        const priceDisplay = h.product_price ? `Rp ${parseInt(h.product_price).toLocaleString('id-ID')}` : '-';
        
        row.innerHTML = `
          <td>${formattedDate}</td>
          <td><strong>${String(h.product?.name || 'N/A').toUpperCase()}</strong></td>
          <td>${priceDisplay}</td>
          <td>${h.quantity}</td>
          <td>${discountDisplay}</td>
          <td>${totalPriceDisplay}</td>
          <td><span class="badge bg-label-info">${String(h.transaction_type || '-').toUpperCase()}</span></td>
          
          <td>
            <div class="d-flex">
              <a class="btn btn-sm btn-icon btn-outline-danger" href="javascript:void(0);" 
                 data-bs-toggle="modal" data-bs-target="#deleteHistoryModal" onclick="prepareDelete(${h.id})">
                <i class="ri-delete-bin-line"></i>
              </a>
            </div>
          </td>
        `;
        tbody.appendChild(row);
      });
    }

    function renderPagination(data) {
      const ul = document.getElementById('historiesPagination');
      ul.innerHTML = '';

      const prevLi = document.createElement('li');
      prevLi.className = `page-item ${data.current_page === 1 ? 'disabled' : ''}`;
      prevLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchHistories(${data.current_page - 1})"><i class="ri-arrow-left-s-line"></i></a>`;
      ul.appendChild(prevLi);

      for (let i = 1; i <= data.last_page; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === data.current_page ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchHistories(${i})">${i}</a>`;
        ul.appendChild(li);
      }

      const nextLi = document.createElement('li');
      nextLi.className = `page-item ${data.current_page === data.last_page ? 'disabled' : ''}`;
      nextLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchHistories(${data.current_page + 1})"><i class="ri-arrow-right-s-line"></i></a>`;
      ul.appendChild(nextLi);
    }

    async function submitSingle() {
      const date = document.getElementById('singleDate').value;
      // const productId = document.getElementById('singleProductSelect').value;
      const qty = document.getElementById('singleQty').value;
      const type = document.getElementById('singleType').value;
      const discPercent = document.getElementById('singleDiscPercent').value;
      const discPrice = document.getElementById('singleDiscPrice').value;


      // Find product name from selected product
      // const selectedOption = document.getElementById('singleProductSelect');
      // const productName = selectedOption.options[selectedOption.selectedIndex].text;
      const productName = document.getElementById('singleProductSearch').value;
      
      if (!date || !qty || !type) {
        showAlert('Please fill all required fields', 'warning');
        return;
      }

      const payload = [{
        date,
        product_name: productName.toLowerCase(),
        quantity: parseInt(qty),
        transaction_type: type.toLowerCase(),
        discount_percent: discPercent ? parseFloat(discPercent) : null,
        discount_price: discPrice ? parseFloat(discPrice) : null
      }];

      try {
        const res = await fetch(`${API_URL}/branch/histories/products`, {
          method: 'POST',
          headers: authHeaders(),
          body: JSON.stringify({ items: payload })
        });
        const result = await res.json();

        if (result.inserted > 0) {
          showAlert(`✓ ${result.inserted} record(s) inserted successfully`, 'success');
          document.getElementById('addHistoryForm').reset();
          document.getElementById('singleProductDropdown').style.display = 'none';
          bootstrap.Modal.getInstance(document.getElementById('addHistoryModal')).hide();
          fetchHistories(1);
        }

        if (result.errors_count > 0) {
          const errors = result.errors.map(e => `Row ${e.index + 1}: ${e.errors.join(', ')}`).join('\n');
          showAlert(`⚠ ${result.errors_count} error(s):\n${errors}`, 'warning');
        }
      } catch (err) {
        console.error(err);
        showAlert('Failed to submit record', 'danger');
      }
    }

    function handleFilePreview() {
      const fileInput = document.getElementById('bulkFile');
      const file = fileInput.files[0];

      if (!file) {
        showAlert('Please select a file', 'warning');
        return;
      }

      const reader = new FileReader();
      reader.onload = (e) => {
        try {
          const data = e.target.result;
          const workbook = XLSX.read(data, { type: 'binary' });
          const sheetName = workbook.SheetNames[0];
          const rows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);

          const cleaned = rows.map(row => ({
            date: row['Tanggal'] || '',
            product_name: (row['Produk'] || '').toLowerCase().trim(),
            quantity: Number(row['Jumlah'] || 0),
            transaction_type: (row['Jenis Penjualan'] || '').toLowerCase().trim(),
            discount_percent: row['Diskon (Persen)'] ? String(row['Diskon (Persen)']).replace(/%/g, '') : null,
            discount_price: row['Diskon (Nominal)'] || null
          })).filter(r => r.date && r.product_name && r.quantity > 0);

          bulkDataCache = cleaned;
          renderBulkPreview(cleaned);
        } catch (err) {
          console.error(err);
          showAlert('Failed to read Excel file', 'danger');
        }
      };
      reader.readAsBinaryString(file);
    }

    function renderBulkPreview(data) {
      const tbody = document.getElementById('bulkPreviewBody');
      tbody.innerHTML = '';

      data.forEach(item => {
        const row = document.createElement('tr');
        const discountPercentDisplay = item.discount_percent ? `${item.discount_percent}%` : '-';
        const discountPriceDisplay = item.discount_price ? `Rp ${parseInt(item.discount_price).toLocaleString('id-ID')}` : '-';
        
        row.innerHTML = `
          <td>${item.date}</td>
          <td>${item.product_name.toUpperCase()}</td>
          <td>${item.quantity}</td>
          <td>${item.transaction_type.toUpperCase()}</td>
          <td>${discountPercentDisplay}</td>
          <td>${discountPriceDisplay}</td>
        `;
        tbody.appendChild(row);
      });

      document.getElementById('previewRowCount').textContent = data.length;
      document.getElementById('bulkPreviewContainer').style.display = 'block';
    }

    async function submitBulk() {
      if (bulkDataCache.length === 0) {
        showAlert('Please preview and select data first', 'warning');
        return;
      }

      try {
        const res = await fetch(`${API_URL}/branch/histories/products`, {
          method: 'POST',
          headers: authHeaders(),
          body: JSON.stringify({ items: bulkDataCache })
        });

        const result = await res.json();

        if (result.inserted > 0) {
          showAlert(`✓ ${result.inserted} record(s) imported successfully`, 'success');
          bulkDataCache = [];
          document.getElementById('bulkFile').value = '';
          document.getElementById('bulkPreviewContainer').style.display = 'none';
          bootstrap.Modal.getInstance(document.getElementById('bulkImportModal')).hide();
          fetchHistories(1);
        }

        if (result.errors_count > 0) {
          const errors = result.errors.map(e => `Row ${e.index + 1}: ${e.errors.join(', ')}`).join('\n');
          showAlert(`⚠ ${result.errors_count} error(s):\n${errors}`, 'warning');
        }
      } catch (err) {
        console.error(err);
        showAlert('Failed to submit bulk data', 'danger');
      }
    }

    function prepareDelete(id) {
      recordToDelete = id;
    }

    async function confirmDelete() {
      if (!recordToDelete) return;

      try {
        const res = await fetch(`${API_URL}/branch/histories/products/${recordToDelete}`, {
          method: 'DELETE',
          headers: authHeaders()
        });

        if (res.ok) {
          showAlert('✓ Record deleted successfully', 'success');
          bootstrap.Modal.getInstance(document.getElementById('deleteHistoryModal')).hide();
          fetchHistories(currentPage);
        } else {
          showAlert('Failed to delete record', 'danger');
        }
      } catch (err) {
        console.error(err);
        showAlert('Delete failed', 'danger');
      }
    }

    // Event Listeners
    document.getElementById('submitSingleBtn').addEventListener('click', submitSingle);
    document.getElementById('bulkFile').addEventListener('change', handleFilePreview);
    document.getElementById('submitBulkBtn').addEventListener('click', submitBulk);
    document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDelete);
    
    // Date and search filter event listeners
    document.getElementById('startDate').addEventListener('change', () => fetchHistories(1));
    document.getElementById('endDate').addEventListener('change', () => fetchHistories(1));
    document.getElementById('searchHistory').addEventListener('input', () => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => fetchHistories(1), 400);
    });

    // Product search for single insertion
    // let singleProductsCache = [];

    // async function fetchBranchProductsForSelection(search = '') {
    //   try {
    //     const url = search 
    //       ? `${API_URL}/branch/products?search=${encodeURIComponent(search)}` 
    //       : `${API_URL}/branch/products`;
    //     const res = await fetch(url, { headers: authHeaders() });
    //     const data = await res.json();
    //     singleProductsCache = data.data || [];
    //     renderSingleProductDropdown(data.data);
    //   } catch (err) {
    //     console.error('Error fetching branch products for selection', err);
    //   }
    // }

    // function renderSingleProductDropdown(products) {
    //   const dropdown = document.getElementById('singleProductDropdown');
    //   dropdown.innerHTML = '';
    //   if (!products || products.length === 0) {
    //     dropdown.innerHTML = '<div class="p-2 text-muted">No products found</div>';
    //     return;
    //   }
    //   products.forEach(p => {
    //     const div = document.createElement('div');
    //     div.className = 'p-2 border-bottom cursor-pointer';
    //     div.style.cursor = 'pointer';
    //     div.innerHTML = `
    //       <div><strong>${p.product?.name || 'Unknown'}</strong></div>
    //       <small class="text-muted">Branch Price: Rp ${Number(p.branch_price || 0).toLocaleString('id-ID')}</small>
    //     `;
    //     div.onclick = () => selectSingleProduct(p.id, p.product?.name || 'Unknown');
    //     dropdown.appendChild(div);
    //   });
    // }

    // function selectSingleProduct(id, name) {
    //   document.getElementById('singleProductSearch').value = name;
    //   document.getElementById('singleProductSelect').value = id;
    //   document.getElementById('singleProductDropdown').style.display = 'none';
    // }

    // document.getElementById('singleProductSearch').addEventListener('input', (e) => {
    //   const search = e.target.value.trim();
    //   if (search.length > 0) {
    //     document.getElementById('singleProductDropdown').style.display = 'block';
    //     fetchBranchProductsForSelection(search);
    //   } else {
    //     document.getElementById('singleProductDropdown').style.display = 'none';
    //   }
    // });

    // document.getElementById('singleProductSelect').addEventListener('focus', () => {
    //   if (singleProductsCache.length === 0) {
    //     document.getElementById('singleProductDropdown').style.display = 'block';
    //     fetchBranchProductsForSelection();
    //   }
    // });
let singleProductsCache = [];

async function fetchBranchProductsForSelection(search = '') {
  try {
    const url = search
      ? `${API_URL}/branch/products?search=${encodeURIComponent(search)}`
      : `${API_URL}/branch/products`;

    const res = await fetch(url, { headers: authHeaders() });
    const data = await res.json();
    singleProductsCache = data.data || [];
    renderSingleProductDropdown(singleProductsCache);
  } catch (err) {
    console.error('Error fetching products', err);
  }
}

function renderSingleProductDropdown(products) {
  const dropdown = document.getElementById('singleProductDropdown');
  dropdown.innerHTML = '';

  if (!products || products.length === 0) {
    dropdown.innerHTML = '<div class="p-2 text-muted">No products found</div>';
    dropdown.style.display = 'block';
    return;
  }

  products.forEach(p => {
    const div = document.createElement('div');
    div.className = 'p-2 border-bottom';
    div.style.cursor = 'pointer';
    div.innerHTML = `
      <strong>${p.product?.name || 'Unknown'}</strong><br>
      <small class="text-muted">Branch Price: Rp ${Number(p.branch_price || 0).toLocaleString('id-ID')}</small>
    `;
    div.onclick = () => selectSingleProduct(p.id, p.product?.name);
    dropdown.appendChild(div);
  });

  dropdown.style.display = 'block';
}

function selectSingleProduct(id, name) {
  selectedSingleProductId = id;
  document.getElementById('singleProductSearch').value = name;
  document.getElementById('singleProductDropdown').style.display = 'none';
}

document.getElementById('singleProductSearch').addEventListener('input', (e) => {
  const search = e.target.value.trim();
  if (search.length > 0) {
    fetchBranchProductsForSelection(search);
  } else {
    document.getElementById('singleProductDropdown').style.display = 'none';
  }
});

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
      // Set default date range to current month
      document.getElementById('startDate').value = formatForInput(getFirstDayOfMonth());
      document.getElementById('endDate').value = formatForInput(getLastDayOfMonth());
      fetchHistories(1);
    });

    // Download template functionality
    document.getElementById('downloadTemplateBtn').addEventListener('click', () => {
      const link = document.createElement('a');
      link.href = '{{ asset("assets/templates/product_history_template.xlsx") }}';
      link.download = 'product_history_template.xlsx';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    });
  </script>

@endsection
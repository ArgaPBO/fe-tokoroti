@extends('layouts.contentNavbarLayoutBranch')

@section('title', 'Expense History')

@section('content')

  <div class="card">

    <!-- ===== Action Bar ===== -->
    <div class="card-header d-flex flex-column flex-md-row align-items-center justify-content-between">
      <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
        <i class="ri-add-line me-1"></i> Add Single
      </button>
      <button class="btn btn-info" type="button" data-bs-toggle="modal" data-bs-target="#bulkImportExpenseModal">
        <i class="ri-upload-cloud-line me-1"></i> Bulk Import
      </button>
    </div>

    <!-- ===== Filter Bar ===== -->
    <div class="card-body pb-0">
      <div class="row g-3">
        <div class="col-md-3">
          <label for="expenseStartDate" class="form-label">Start Date</label>
          <input type="date" class="form-control" id="expenseStartDate">
        </div>
        <div class="col-md-3">
          <label for="expenseEndDate" class="form-label">End Date</label>
          <input type="date" class="form-control" id="expenseEndDate">
        </div>
        <div class="col-md-6">
          <label for="searchExpenseHistory" class="form-label">Search Expense</label>
          <input type="text" class="form-control" id="searchExpenseHistory" placeholder="Search expense name...">
        </div>
      </div>
    </div>

    <!-- ===== Expense History Table ===== -->
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Pengeluaran</th>
            <th>Nominal</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0" id="expenseHistoriesTableBody">
          {{-- Data akan diisi oleh JavaScript --}}
        </tbody>
      </table>
    </div>

    <!-- ===== Pagination ===== -->
    <div class="card-footer d-flex justify-content-center">
      <nav aria-label="Page navigation">
        <ul class="pagination mb-0" id="expenseHistoriesPagination">
          {{-- Pagination diisi oleh JS --}}
        </ul>
      </nav>
    </div>

  </div>

  <!-- ===== MODALS ===== -->

  <!-- 1. Modal Add Single Expense -->
  <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addExpenseModalLabel">Add Expense Transaction</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="addExpenseForm">
            <div class="mb-3">
              <label for="singleExpenseDate" class="form-label">Date <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="singleExpenseDate" required>
            </div>
            <div class="mb-3">
              <label for="singleExpenseSearch" class="form-label">Expense Type <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="singleExpenseSearch" placeholder="Search expenses...">
            </div>
            <div class="mb-3">
              <label for="singleExpenseSelect" class="form-label">Select Expense <span class="text-danger">*</span></label>
              <select class="form-select" id="singleExpenseSelect" required>
                <option value="">Choose an expense...</option>
              </select>
              <div id="singleExpenseDropdown" class="mt-2" style="max-height: 250px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px; display: none;">
                <!-- Expense list will be populated here -->
              </div>
            </div>
            <div class="mb-3">
              <label for="singleNominal" class="form-label">Nominal Rp <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="singleNominal" placeholder="e.g., 50000" required>
            </div>
            <div class="mb-3">
              <label for="singleDescription" class="form-label">Description</label>
              <textarea class="form-control" id="singleDescription" placeholder="Optional notes..." rows="3"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="submitExpenseBtn">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Modal Bulk Import Expense -->
  <div class="modal fade" id="bulkImportExpenseModal" tabindex="-1" aria-labelledby="bulkImportExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="bulkImportExpenseModalLabel">Bulk Import Expense History</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="bulkExpenseFile" class="form-label">Choose Excel File <span class="text-danger">*</span></label>
            <input type="file" class="form-control" id="bulkExpenseFile" accept=".xlsx,.xls">
            <small class="text-muted d-block mt-2">Columns: Tanggal, Pengeluaran, Nominal, Deskripsi</small>
          </div>

          <!-- Preview Table -->
          <div id="bulkExpensePreviewContainer" style="display: none;">
            <h6>Preview (will import <strong id="expensePreviewRowCount">0</strong> rows)</h6>
            <div class="table-responsive">
              <table class="table table-sm table-hover">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Expense</th>
                    <th>Nominal</th>
                    <th>Description</th>
                  </tr>
                </thead>
                <tbody id="bulkExpensePreviewBody">
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="submitBulkExpenseBtn">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Modal Delete Confirmation -->
  <div class="modal fade" id="deleteExpenseModal" tabindex="-1" aria-labelledby="deleteExpenseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteExpenseModalLabel">Delete Record?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this expense history? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger" id="confirmDeleteExpenseBtn">Delete</button>
        </div>
      </div>
    </div>
  </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
  <script>
    const API_URL = '{{ env("API_URL") }}';
    let currentExpensePage = 1;
    let bulkExpenseDataCache = [];
    let expenseRecordToDelete = null;

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

    async function fetchExpenseHistories(page = 1) {
      try {
        let url = `${API_URL}/branch/histories/expenses?page=${page}`;
        const startDate = document.getElementById('expenseStartDate').value;
        const endDate = document.getElementById('expenseEndDate').value;
        const search = document.getElementById('searchExpenseHistory').value.trim();
        
        if (startDate) url += `&date_from=${encodeURIComponent(startDate)}`;
        if (endDate) url += `&date_to=${encodeURIComponent(endDate)}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        
        const res = await fetch(url, { headers: authHeaders(), credentials: 'include' });
        if (!res.ok) throw new Error('Failed to fetch');
        const data = await res.json();
        renderExpenseHistoriesTable(data.data);
        renderExpensePagination(data);
        currentExpensePage = page;
      } catch (err) {
        console.error('Error fetching expense histories:', err);
        showAlert('Failed to load expense histories', 'danger');
      }
    }

    function renderExpenseHistoriesTable(histories) {
      const tbody = document.getElementById('expenseHistoriesTableBody');
      tbody.innerHTML = '';
      if (!histories || histories.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center">No records found</td></tr>';
        return;
      }
      histories.forEach(h => {
        const row = document.createElement('tr');
        const dateObj = new Date(h.date);
        const formattedDate = dateObj.toLocaleDateString('id-ID');
        const nominalDisplay = `Rp ${parseInt(h.nominal).toLocaleString('id-ID')}`;
        const description = h.description || '-';
        
        row.innerHTML = `
          <td>${formattedDate}</td>
          <td><strong>${String(h.expense?.name || 'N/A').toUpperCase()}</strong></td>
          <td>${nominalDisplay}</td>
          <td>${description}</td>
          <td>
            <div class="d-flex">
              <a class="btn btn-sm btn-icon btn-outline-danger" href="javascript:void(0);" 
                 data-bs-toggle="modal" data-bs-target="#deleteExpenseModal" onclick="prepareDeleteExpense(${h.id})">
                <i class="ri-delete-bin-line"></i>
              </a>
            </div>
          </td>
        `;
        tbody.appendChild(row);
      });
    }

    function renderExpensePagination(data) {
      const ul = document.getElementById('expenseHistoriesPagination');
      ul.innerHTML = '';

      const prevLi = document.createElement('li');
      prevLi.className = `page-item ${data.current_page === 1 ? 'disabled' : ''}`;
      prevLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchExpenseHistories(${data.current_page - 1})"><i class="ri-arrow-left-s-line"></i></a>`;
      ul.appendChild(prevLi);

      for (let i = 1; i <= data.last_page; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === data.current_page ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchExpenseHistories(${i})">${i}</a>`;
        ul.appendChild(li);
      }

      const nextLi = document.createElement('li');
      nextLi.className = `page-item ${data.current_page === data.last_page ? 'disabled' : ''}`;
      nextLi.innerHTML = `<a class="page-link" href="javascript:void(0);" onclick="fetchExpenseHistories(${data.current_page + 1})"><i class="ri-arrow-right-s-line"></i></a>`;
      ul.appendChild(nextLi);
    }

    async function submitExpense() {
      const date = document.getElementById('singleExpenseDate').value;
      const expenseName = document.getElementById('singleExpenseSearch').value;
      const nominal = document.getElementById('singleNominal').value;
      const description = document.getElementById('singleDescription').value;

      if (!date || !expenseName || !nominal) {
        showAlert('Please fill all required fields', 'warning');
        return;
      }

      const payload = [{
        date,
        expense_name: expenseName.toLowerCase(),
        nominal: parseFloat(nominal),
        description: description || null
      }];

      try {
        const res = await fetch(`${API_URL}/branch/histories/expenses`, {
          method: 'POST',
          headers: authHeaders(),
          credentials: 'include',
          body: JSON.stringify({ items: payload })
        });
        const result = await res.json();

        if (result.inserted > 0) {
          showAlert(`✓ ${result.inserted} record(s) inserted successfully`, 'success');
          document.getElementById('addExpenseForm').reset();
          document.getElementById('singleExpenseDropdown').style.display = 'none';
          bootstrap.Modal.getInstance(document.getElementById('addExpenseModal')).hide();
          fetchExpenseHistories(1);
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

    function handleExpenseFilePreview() {
      const fileInput = document.getElementById('bulkExpenseFile');
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
            expense_name: (row['Jenis Pengeluaran'] || '').toLowerCase().trim(),
            nominal: Number(row['Nominal'] || 0),
            description: row['Deskripsi'] || null
          })).filter(r => r.date && r.expense_name && r.nominal > 0);

          bulkExpenseDataCache = cleaned;
          renderBulkExpensePreview(cleaned);
        } catch (err) {
          console.error(err);
          showAlert('Failed to read Excel file', 'danger');
        }
      };
      reader.readAsBinaryString(file);
    }

    function renderBulkExpensePreview(data) {
      const tbody = document.getElementById('bulkExpensePreviewBody');
      tbody.innerHTML = '';

      data.forEach(item => {
        const row = document.createElement('tr');
        const nominalDisplay = `Rp ${parseInt(item.nominal).toLocaleString('id-ID')}`;
        
        row.innerHTML = `
          <td>${item.date}</td>
          <td>${item.expense_name.toUpperCase()}</td>
          <td>${nominalDisplay}</td>
          <td>${item.description || '-'}</td>
        `;
        tbody.appendChild(row);
      });

      document.getElementById('expensePreviewRowCount').textContent = data.length;
      document.getElementById('bulkExpensePreviewContainer').style.display = 'block';
    }

    async function submitBulkExpense() {
      if (bulkExpenseDataCache.length === 0) {
        showAlert('Please select file and preview data first', 'warning');
        return;
      }

      try {
        const res = await fetch(`${API_URL}/branch/histories/expenses`, {
          method: 'POST',
          headers: authHeaders(),
          credentials: 'include',
          body: JSON.stringify({ items: bulkExpenseDataCache })
        });

        const result = await res.json();

        if (result.inserted > 0) {
          showAlert(`✓ ${result.inserted} record(s) imported successfully`, 'success');
          bulkExpenseDataCache = [];
          document.getElementById('bulkExpenseFile').value = '';
          document.getElementById('bulkExpensePreviewContainer').style.display = 'none';
          bootstrap.Modal.getInstance(document.getElementById('bulkImportExpenseModal')).hide();
          fetchExpenseHistories(1);
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

    function prepareDeleteExpense(id) {
      expenseRecordToDelete = id;
    }

    async function confirmDeleteExpense() {
      if (!expenseRecordToDelete) return;

      try {
        const res = await fetch(`${API_URL}/branch/histories/expenses/${expenseRecordToDelete}`, {
          method: 'DELETE',
          headers: authHeaders(),
          credentials: 'include'
        });

        if (res.ok) {
          showAlert('✓ Record deleted successfully', 'success');
          bootstrap.Modal.getInstance(document.getElementById('deleteExpenseModal')).hide();
          fetchExpenseHistories(currentExpensePage);
        } else {
          showAlert('Failed to delete record', 'danger');
        }
      } catch (err) {
        console.error(err);
        showAlert('Delete failed', 'danger');
      }
    }

    // Event Listeners
    document.getElementById('submitExpenseBtn').addEventListener('click', submitExpense);
    document.getElementById('bulkExpenseFile').addEventListener('change', handleExpenseFilePreview);
    document.getElementById('submitBulkExpenseBtn').addEventListener('click', submitBulkExpense);
    document.getElementById('confirmDeleteExpenseBtn').addEventListener('click', confirmDeleteExpense);
    
    // Date and search filter event listeners
    document.getElementById('expenseStartDate').addEventListener('change', () => fetchExpenseHistories(1));
    document.getElementById('expenseEndDate').addEventListener('change', () => fetchExpenseHistories(1));
    document.getElementById('searchExpenseHistory').addEventListener('input', () => {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => fetchExpenseHistories(1), 400);
    });

    // Expense search for single insertion
    let singleExpensesCache = [];

    async function fetchExpensesForSelection(search = '') {
      try {
        const url = search 
          ? `${API_URL}/expenses?search=${encodeURIComponent(search)}` 
          : `${API_URL}/expenses`;
        const res = await fetch(url, { headers: authHeaders(), credentials: 'include' });
        const data = await res.json();
        singleExpensesCache = data.data || [];
        renderSingleExpenseDropdown(data.data);
      } catch (err) {
        console.error('Error fetching expenses for selection', err);
      }
    }

    function renderSingleExpenseDropdown(expenses) {
      const dropdown = document.getElementById('singleExpenseDropdown');
      dropdown.innerHTML = '';
      if (!expenses || expenses.length === 0) {
        dropdown.innerHTML = '<div class="p-2 text-muted">No expenses found</div>';
        return;
      }
      expenses.forEach(e => {
        const div = document.createElement('div');
        div.className = 'p-2 border-bottom cursor-pointer';
        div.style.cursor = 'pointer';
        div.innerHTML = `<div><strong>${e.name || 'Unknown'}</strong></div>`;
        div.onclick = () => selectSingleExpense(e.id, e.name || 'Unknown');
        dropdown.appendChild(div);
      });
    }

    function selectSingleExpense(id, name) {
      document.getElementById('singleExpenseSearch').value = name;
      document.getElementById('singleExpenseSelect').value = id;
      document.getElementById('singleExpenseDropdown').style.display = 'none';
    }

    document.getElementById('singleExpenseSearch').addEventListener('input', (e) => {
      const search = e.target.value.trim();
      if (search.length > 0) {
        document.getElementById('singleExpenseDropdown').style.display = 'block';
        fetchExpensesForSelection(search);
      } else {
        document.getElementById('singleExpenseDropdown').style.display = 'none';
      }
    });

    document.getElementById('singleExpenseSelect').addEventListener('focus', () => {
      if (singleExpensesCache.length === 0) {
        document.getElementById('singleExpenseDropdown').style.display = 'block';
        fetchExpensesForSelection();
      }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
      // Set default date range to current month
      document.getElementById('expenseStartDate').value = formatForInput(getFirstDayOfMonth());
      document.getElementById('expenseEndDate').value = formatForInput(getLastDayOfMonth());
      fetchExpenseHistories(1);
    });
  </script>

@endsection

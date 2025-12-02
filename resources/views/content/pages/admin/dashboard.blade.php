@extends('layouts.contentNavbarLayout')

@section('title', 'Dashboard')

@section('content')

  <!-- ===== Dashboard Stats ===== -->
  {{--
  Bootstrap Grid:
  - row g-4 (memberi gutter/jarak)
  - col-lg-3 (3*4 = 12, jadi 4 kartu per baris di layar besar)
  - col-md-6 (6*2 = 12, jadi 2 kartu per baris di tablet)
  - col-12 (1 kartu per baris di HP)
  --}}
  <div class="row g-4 mb-4">

    <div class="col-12 col-md-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Products</h5>
            <span class="avatar">
              <span class="avatar-initial rounded bg-label-success"><i class="ri-store-2-line"></i></span>
            </span>
          </div>
          <h4 class="card-value fw-medium mb-1" id="productsCount">—</h4>
          <small class="text-muted">Total products</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Branches</h5>
            <span class="avatar">
              <span class="avatar-initial rounded bg-label-primary"><i class="ri-map-pin-2-line"></i></span>
            </span>
          </div>
          <h4 class="card-value fw-medium mb-1" id="branchesCount">—</h4>
          <small class="text-muted">Total branches</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Products Sold (This Month)</h5>
            <span class="avatar">
              <span class="avatar-initial rounded bg-label-warning"><i class="ri-shopping-bag-3-line"></i></span>
            </span>
          </div>
          <h4 class="card-value fw-medium mb-1" id="phQuantitySum">—</h4>
          <small class="text-muted">Sum quantity this month</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Expenses (This Month)</h5>
            <span class="avatar">
              <span class="avatar-initial rounded bg-label-danger"><i class="ri-file-dollar-line"></i></span>
            </span>
          </div>
          <h4 class="card-value fw-medium mb-1" id="expenseNominalSum">—</h4>
          <small class="text-muted">Sum expenses this month</small>
        </div>
      </div>
    </div>
  </div>

  

<script>
  (function(){
    const API_URL = '{{ env("API_URL") }}';

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

    async function fetchAdminDashboard() {
      try {
        const url = `${API_URL}/admin/dashboard`;
        const opts = { headers: authHeaders(), credentials: 'include' };
        console.log('Fetching admin dashboard', url, opts);
        const res = await fetch(url, opts);
        console.log('Admin dashboard status', res.status, res.statusText);
        if (!res.ok) {
          const txt = await res.text().catch(()=>null);
          console.error('Admin dashboard body:', txt);
          throw new Error(`Failed to load admin dashboard: ${res.status}`);
        }
        const data = await res.json();
        console.log('Admin dashboard data', data);

        document.getElementById('productsCount').textContent = data.products_count ?? '0';
        document.getElementById('branchesCount').textContent = data.branches_count ?? '0';
        document.getElementById('phQuantitySum').textContent = data.branch_product_history_quantity_sum ?? '0';
        const expenseEl = document.getElementById('expenseNominalSum');
        const expense = Number(data.branch_expense_history_nominal_sum || 0);
        expenseEl.textContent = expense > 0 ? 'Rp ' + expense.toLocaleString('id-ID') : 'Rp 0';
      } catch (err) {
        console.error('fetchAdminDashboard error', err);
        const container = document.querySelector('.row.g-4');
        if (container) {
          const msg = document.createElement('div');
          msg.className = 'alert alert-warning';
          msg.innerHTML = `Gagal memuat data admin dashboard. Periksa autentikasi/CORS. <br><small>${err.message}</small>`;
          container.parentNode.insertBefore(msg, container);
        }
      }
    }

    document.addEventListener('DOMContentLoaded', fetchAdminDashboard);
  })();
</script>

@endsection
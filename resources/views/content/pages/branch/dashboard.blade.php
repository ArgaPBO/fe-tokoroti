@extends('layouts.contentNavbarLayout')

@section('title', 'Branch Dashboard')

@section('content')
  <div class="container-fluid py-4">
    <!-- ===== Branch Stats (live) ===== -->
    <div class="row g-4 mb-4">
      <!-- Branch Product Count -->
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card">
          <div class="card-body d-flex align-items-center">
            <div class="avatar me-3 flex-shrink-0" style="background-color: #e8f5e9;">
              <span class="avatar-initial rounded bg-label-success"><i class="ri-store-2-line ri-24px"></i></span>
            </div>
            <div>
              <p class="mb-0 text-muted small">Branch Products</p>
              <h4 class="card-title mb-0" id="productCount">—</h4>
              <small class="text-muted">Total products in this branch</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Product History Count (this month) -->
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card">
          <div class="card-body d-flex align-items-center">
            <div class="avatar me-3 flex-shrink-0" style="background-color: #fff3e0;">
              <span class="avatar-initial rounded bg-label-warning"><i class="ri-shopping-bag-3-line ri-24px"></i></span>
            </div>
            <div>
              <p class="mb-0 text-muted small">Products Sold (This Month)</p>
              <h4 class="card-title mb-0" id="productHistoryCount">—</h4>
              <small class="text-muted">Sum quantity for current month</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Expense History (this month) -->
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card">
          <div class="card-body d-flex align-items-center">
            <div class="avatar me-3 flex-shrink-0" style="background-color: #e3f2fd;">
              <span class="avatar-initial rounded bg-label-primary"><i class="ri-file-dollar-line ri-24px"></i></span>
            </div>
            <div>
              <p class="mb-0 text-muted small">Expenses (This Month)</p>
              <h4 class="card-title mb-0" id="expenseHistoryCount">—</h4>
              <small class="text-muted">Sum expenses for current month</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Transactions (not available) -->
    {{-- <div class="card">
      <h5 class="card-header">Transaksi Terbaru</h5>
      <div class="card-body">
        <p class="text-muted">Recent transactions are not shown on this dashboard. Use the Transactions page for details.</p>
      </div>
    </div> --}}
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

    async function fetchDashboard() {
      try {
        const res = await fetch(`${API_URL}/branch/dashboard`, { headers: authHeaders() });
        if (!res.ok) throw new Error('Failed to load dashboard');
        const data = await res.json();

        // Expecting keys: branch_product_count, branch_product_history_count, branch_expense_history_count
        document.getElementById('productCount').textContent = data.branch_product_count ?? '0';
        document.getElementById('productHistoryCount').textContent = data.branch_product_history_count ?? '0';
        // Format expense as IDR currency
        const expenseEl = document.getElementById('expenseHistoryCount');
        const expense = Number(data.branch_expense_history_count || 0);
        expenseEl.textContent = expense > 0 ? 'Rp ' + expense.toLocaleString('id-ID') : 'Rp 0';
      } catch (err) {
        console.error(err);
        // keep placeholders but show small alert
        const container = document.querySelector('.container-fluid');
        if (container) {
          const msg = document.createElement('div');
          msg.className = 'alert alert-warning';
          msg.textContent = 'Gagal memuat data dashboard. Silakan muat ulang halaman.';
          container.insertBefore(msg, container.firstChild);
        }
      }
    }

    document.addEventListener('DOMContentLoaded', fetchDashboard);
  })();
</script>
@endsection
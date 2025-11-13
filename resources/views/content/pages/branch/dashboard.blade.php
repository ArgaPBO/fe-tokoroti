@extends('layouts.contentNavbarLayout')

@section('title', 'Branch Dashboard')

@section('content')
  <div class="container-fluid py-4">
    <!-- ===== Statistik Hari Ini ===== -->
    <div class="row g-4 mb-4">
      <!-- Total Transaksi -->
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card">
          <div class="card-body d-flex align-items-center">
            <div class="avatar me-3 flex-shrink-0" style="background-color: #e8f5e9;">
              <span class="avatar-initial rounded bg-label-success"><i class="ri-exchange-dollar-line ri-24px"></i></span>
            </div>
            <div>
              <p class="mb-0 text-muted small">Total Transaksi</p>
              <h4 class="card-title mb-0">124</h4>
              <small class="text-success">+12% dari kemarin</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Revenue -->
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card">
          <div class="card-body d-flex align-items-center">
            <div class="avatar me-3 flex-shrink-0" style="background-color: #e3f2fd;">
              <span class="avatar-initial rounded bg-label-primary"><i
                  class="ri-money-dollar-circle-line ri-24px"></i></span>
            </div>
            <div>
              <p class="mb-0 text-muted small">Total Revenue</p>
              <h4 class="card-title mb-0">Rp 8.750.000</h4>
              <small class="text-success">+8% dari kemarin</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Produk Terjual -->
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card">
          <div class="card-body d-flex align-items-center">
            <div class="avatar me-3 flex-shrink-0" style="background-color: #fff3e0;">
              <span class="avatar-initial rounded bg-label-warning"><i class="ri-shopping-bag-3-line ri-24px"></i></span>
            </div>
            <div>
              <p class="mb-0 text-muted small">Produk Terjual</p>
              <h4 class="card-title mb-0">320</h4>
              <small class="text-danger">-3% dari kemarin</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== Recent Transactions ===== -->
    <div class="card">
      <h5 class="card-header">Transaksi Terbaru</h5>
      <div class="table-responsive text-nowrap">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>ID Transaksi</th>
              <th>Waktu</th>
              <th>Total Amount</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            <tr>
              <td><strong>#TRX-2025-0113-001</strong></td>
              <td>13:45</td>
              <td><span class="badge bg-label-success">Rp 125.000</span></td>
            </tr>
            <tr>
              <td><strong>#TRX-2025-0113-002</strong></td>
              <td>13:20</td>
              <td><span class="badge bg-label-success">Rp 89.000</span></td>
            </tr>
            <tr>
              <td><strong>#TRX-2025-0113-003</strong></td>
              <td>12:55</td>
              <td><span class="badge bg-label-success">Rp 200.000</span></td>
            </tr>
            <tr>
              <td><strong>#TRX-2025-0113-004</strong></td>
              <td>12:30</td>
              <td><span class="badge bg-label-info">Rp 45.000</span></td>
            </tr>
            <tr>
              <td><strong>#TRX-2025-0113-005</strong></td>
              <td>12:10</td>
              <td><span class="badge bg-label-success">Rp 150.000</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

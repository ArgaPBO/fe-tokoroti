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
            <h5 class="mb-0">Total Revenue</h5>
            <span class="avatar">
              <span class="avatar-initial rounded bg-label-success"><i class="ri-money-dollar-circle-line"></i></span>
            </span>
          </div>
          <h4 class="card-value fw-medium mb-1">Rp 4.500.000</h4>
          <small class="text-success">(+20%)</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Total Profit</h5>
            <span class="avatar">
              <span class="avatar-initial rounded bg-label-primary"><i class="ri-line-chart-line"></i></span>
            </span>
          </div>
          <h4 class="card-value fw-medium mb-1">Rp 4.500.000</h4>
          <small class="text-success">(+15%)</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Expenses</h5>
            <span class="avatar">
              <span class="avatar-initial rounded bg-label-danger"><i class="ri-shopping-cart-2-line"></i></span>
            </span>
          </div>
          <h4 class="card-value fw-medium mb-1">Rp 4.500.000</h4>
          <small class="text-danger">(-5%)</small>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
      <div class="card">
        <div class="card-body">
          <div class="card-title d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Net Income</h5>
            <span class="avatar">
              <span class="avatar-initial rounded bg-label-info"><i class="ri-wallet-3-line"></i></span>
            </span>
          </div>
          <h4 class="card-value fw-medium mb-1">Rp 4.500.000</h4>
          <small class="text-success">(+10%)</small>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== Charts ===== -->
  <div class="row g-4 mb-4">
    <!-- Chart 1: Profit and Loss -->
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Profit and Loss</h5>
        </div>
        <div class="card-body">
          {{-- Di sini Anda akan meletakkan <canvas> untuk Chart.js --}}
            <div class="chart-placeholder"
              style="height: 300px; display: grid; place-items: center; background: #f9f9f9; border-radius: 4px;">Grafik
              Line (Jan - Agt)</div>
        </div>
      </div>
    </div>

    <!-- Chart 2: Branch Performa -->
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Branch Performa</h5>
        </div>
        <div class="card-body">
          {{-- Saya rapikan juga bagian ini agar lebih terstruktur --}}
          <div class="branch-performa d-flex justify-content-around mb-3">
            {{-- Anda bisa mengganti ini dengan chart donat/lingkaran --}}
            <div class="branch-circle" style="text-align: center;">
              <div
                style="width: 80px; height: 80px; border: 5px solid #696cff; border-radius: 50%; display: grid; place-items: center; font-weight: bold;">
                68%</div>
              <span class="text-muted small">Branch A</span>
            </div>
            <div class="branch-circle" style="text-align: center;">
              <div
                style="width: 80px; height: 80px; border: 5px solid #00cfe8; border-radius: 50%; display: grid; place-items: center; font-weight: bold;">
                89%</div>
              <span class="text-muted small">Branch B</span>
            </div>
            <div class="branch-circle" style="text-align: center;">
              <div
                style="width: 80px; height: 80px; border: 5px solid #ffab00; border-radius: 50%; display: grid; place-items: center; font-weight: bold;">
                42%</div>
              <span class="text-muted small">Branch C</span>
            </div>
          </div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between"><span><i class="ri-circle-fill me-2"
                  style="color: #696cff;"></i>Branch A</span> <span>68%</span></li>
            <li class="list-group-item d-flex justify-content-between"><span><i class="ri-circle-fill me-2"
                  style="color: #00cfe8;"></i>Branch B</span> <span>89%</span></li>
            <li class="list-group-item d-flex justify-content-between"><span><i class="ri-circle-fill me-2"
                  style="color: #ffab00;"></i>Branch C</span> <span>42%</span></li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== Top Selling Products ===== -->
  <div class="card">
    <h5 class="card-header">Top Selling Products</h5>
    {{-- Ini penting untuk tabel agar responsif di HP --}}
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>Products</th>
            <th>Quantity Sold</th>
            <th>Revenue</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          <tr>
            <td><strong>Chiffon Uk 15 Ori</strong></td>
            <td>1200</td>
            <td><span class="badge bg-label-success">Rp 4.500.000</span></td>
          </tr>
          <tr>
            <td><strong>Brownies</strong></td>
            <td>1198</td>
            <td><span class="badge bg-label-success">Rp 4.378.000</span></td>
          </tr>
          <tr>
            <td><strong>Bolu Kotak</strong></td>
            <td>1150</td>
            <td><span class="badge bg-label-success">Rp 4.250.000</span></td>
          </tr>
          <tr>
            <td><strong>Lapis Mandarin</strong></td>
            <td>987</td>
            <td><span class="badge bg-label-info">Rp 3.700.000</span></td>
          </tr>
          <tr>
            <td><strong>Chiffon Uk 18 Extra</strong></td>
            <td>843</td>
            <td><span class="badge bg-label-info">Rp 2.900.000</span></td>
          </tr>
          <tr>
            <td><strong>Krumpul Ring Isi 5 Dua Rasa</strong></td>
            <td>655</td>
            <td><span class="badge bg-label-warning">Rp 980.000</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
@endsection

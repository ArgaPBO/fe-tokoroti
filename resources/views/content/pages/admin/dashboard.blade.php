@php
  $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Dashboard - Toko Kue PAD')

{{-- Hapus bagian vendor-style & page-style yang memuat SCSS tidak ada --}}
{{-- Karena Materialize sudah menyediakan semua class yang kamu butuhkan --}}

@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/apex-charts/apexcharts.js'
  ])
@endsection

@section('page-script')
  {{-- Opsional: jika kamu punya JS khusus --}}
  {{-- @vite(['resources/assets/js/app-dashboard.js']) --}}
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-12 mb-4">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Selamat Datang di Dashboard</h5>
          <small class="text-muted">Hi! Admin</small>
        </div>
        <div class="card-body">
          <p>Ini adalah halaman dashboard utama Toko Kue PAD.</p>
        </div>
      </div>
    </div>

    <!-- Stats -->
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1">Total Revenue</h5>
              <p class="text-muted mb-0">8.98 Jt</p>
            </div>
            <div class="avatar bg-label-primary">
              <i class="ri-money-dollar-circle-line ri-24px"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1">Total Profit</h5>
              <p class="text-muted mb-0">14.7 Jt</p>
            </div>
            <div class="avatar bg-label-success">
              <i class="ri-bar-chart-line ri-24px"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1">Expenses</h5>
              <p class="text-muted mb-0">2.7 Jt</p>
            </div>
            <div class="avatar bg-label-warning">
              <i class="ri-wallet-3-line ri-24px"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1">Net Income</h5>
              <p class="text-muted mb-0">12 Jt</p>
            </div>
            <div class="avatar bg-label-info">
              <i class="ri-hand-coin-line ri-24px"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Top Selling Products -->
    <div class="col-12 mb-4">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Top Selling Products</h5>
        </div>
        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead>
              <tr>
                <th>Products</th>
                <th>Quantity Sold</th>
                <th>Revenue</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Chiffon Uk 15 Ori</td>
                <td>1200</td>
                <td>4.500.000</td>
              </tr>
              <tr>
                <td>Brownies</td>
                <td>1198</td>
                <td>4.378.000</td>
              </tr>
              <tr>
                <td>Bolu Kotak</td>
                <td>1150</td>
                <td>4.250.000</td>
              </tr>
              <tr>
                <td>Lapis Mandarin</td>
                <td>987</td>
                <td>3.700.000</td>
              </tr>
              <tr>
                <td>Chiffon Uk 18 Extra</td>
                <td>843</td>
                <td>2.900.000</td>
              </tr>
              <tr>
                <td>Krumpul Ring Isi 5 Dua Rasa</td>
                <td>655</td>
                <td>980.000</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
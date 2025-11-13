@extends('layouts.contentNavbarLayout')

@section('title', 'Financial Report')

@section('content')

  <!-- Import & Export Section -->
  <div class="row g-4 mb-4">
    <!-- Import File -->
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Import File</h5>
        </div>
        <div class="card-body">
          {{-- Ini adalah style "dropzone" yang lebih pas dengan template Materialize --}}
          <div class="border border-dashed rounded p-4 text-center"
            style="min-height: 240px; display: flex; flex-direction: column; justify-content: center; align-items: center;">
            <i class="ri-upload-cloud-line ri-32px text-primary mb-3"></i>
            <p class="mb-2 fw-medium">Drag & Drop file here</p>
            <p class="mb-3 text-muted">or</p>
            {{-- Menggunakan input file tersembunyi yang dipicu oleh tombol --}}
            <input type="file" id="select-file-input" hidden>
            <label for="select-file-input" class="btn btn-outline-primary btn-sm">
              Select File
            </label>
          </div>
        </div>
      </div>
    </div>

    <!-- Export File -->
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Export File</h5>
        </div>
        <div class="card-body">
          <form>
            <div class="mb-3">
              <label for="branch" class="form-label">Select Branch</label>
              <select id="branch" class="form-select">
                <option selected>Choose...</option>
                <option value="1">Branch A</option>
                <option value="2">Branch B</option>
                <option value="3">Branch C</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="fileType" class="form-label">Type of File</label>
              <select id="fileType" class="form-select">
                <option selected>Choose...</option>
                <option value="csv">CSV</option>
                <option value="xlsx">Excel (.xlsx)</option>
                <option value="pdf">PDF</option>
              </select>
            </div>
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label for="startDate" class="form-label">Start Date</label>
                {{-- Menambahkan class datepicker-input --}}
                <input type="text" class="form-control datepicker-input" id="startDate" placeholder="mm/dd/yyyy" />
              </div>
              <div class="col-md-6">
                <label for="endDate" class="form-label">End Date</label>
                {{-- Menambahkan class datepicker-input --}}
                <input type="text" class="form-control datepicker-input" id="endDate" placeholder="mm/dd/yyyy" />
              </div>
            </div>
            <button type="submit" class="btn btn-warning w-100">Export</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- History Table -->
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">History</h5>
      <div class="input-group input-group-sm" style="max-width: 300px;">
        <span class="input-group-text"><i class="ri-search-line"></i></span>
        <input type="text" class="form-control" placeholder="Search...">
      </div>
    </div>
    {{-- card-body p-0 agar tabelnya pas dengan card --}}
    <div class="card-body p-0">
      <div class="table-responsive text-nowrap">
        <table class="table table-hover mb-0">
          {{-- Menghapus bg-light agar style header-nya ikut template --}}
          <thead>
            <tr>
              <th scope="col">File Name</th>
              <th scope="col">Date</th>
              <th scope="col">Type</th>
              <th scope="col">Status</th>
            </tr>
          </thead>
          <tbody>
            @for ($i = 0; $i < 6; $i++)
              <tr>
                <td><strong>Report_20240115.csv</strong></td>
                <td>15 - 01 - 2024</td>
                <td>@if($i % 2 == 0) Import @else Export @endif</td>
                <td>
                  {{-- Mengganti class badge agar sesuai style template --}}
                  <span class="badge bg-label-success">Completed</span>
                </td>
              </tr>
            @endfor
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">
      <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center mb-0">
          <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">«</a>
          </li>
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item active" aria-current="page">
            <a class="page-link" href="#">3</a>
          </li>
          <li class="page-item"><a class="page-link" href="#">4</a></li>
          <li class="page-item">
            <a class="page-link" href="#">»</a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
@endsection

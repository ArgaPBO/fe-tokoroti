@extends('layouts.contentNavbarLayout')

{{-- Judul ini akan muncul di navbar atas Anda --}}
@section('title', 'Management Branch')

@section('content')

  <div class="card">

    <!-- ===== Tombol Aksi (Action Bar) ===== -->
    <div class="card-header d-flex flex-column flex-md-row align-items-center justify-content-between">

      {{-- Dibiarkan kosong di kiri agar tombol "Add Branch" di kanan --}}
      <div></div>

      {{-- Tombol Sortir dan Tambah Produk --}}
      <div class="d-flex">
        {{-- Tombol Pemicu Modal "Add Branch" --}}
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addBranchModal">
          <i class="ri-add-line me-1"></i> Add Branch
        </button>
      </div>

    </div>

    <!-- ===== Tabel Branch ===== -->
    <div class="table-responsive text-nowrap">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Name</th>
            <th>Locations</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          {{-- Contoh Data Baris 1 --}}
          <tr>
            <td><strong>Branch A</strong></td>
            <td>Yogyakarta City</td>
            <td>
              <div class="d-flex">
                {{-- Tombol Pemicu Modal "Edit" --}}
                <a class="btn btn-sm btn-icon btn-outline-primary me-2" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#editBranchModal">
                  <i class="ri-pencil-line"></i>
                </a>
                {{-- Tombol Pemicu Modal "Delete" --}}
                <a class="btn btn-sm btn-icon btn-outline-danger" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#deleteBranchModal">
                  <i class="ri-delete-bin-line"></i>
                </a>
              </div>
            </td>
          </tr>
          {{-- Contoh Data Baris 2 --}}
          <tr>
            <td><strong>Branch B</strong></td>
            <td>Gunung Kidul</td>

            <td>
              <div class="d-flex">
                {{-- Tombol Pemicu Modal "Edit" --}}
                <a class="btn btn-sm btn-icon btn-outline-primary me-2" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#editBranchModal">
                  <i class="ri-pencil-line"></i>
                </a>
                {{-- Tombol Pemicu Modal "Delete" --}}
                <a class="btn btn-sm btn-icon btn-outline-danger" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#deleteBranchModal">
                  <i class="ri-delete-bin-line"></i>
                </a>
              </div>
            </td>
          </tr>
          {{-- Contoh Data Baris 3 --}}
          <tr>
            <td><strong>Branch C</strong></td>
            <td>Sleman</td>

            <td>
              <div class="d-flex">
                {{-- Tombol Pemicu Modal "Edit" --}}
                <a class="btn btn-sm btn-icon btn-outline-primary me-2" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#editBranchModal">
                  <i class="ri-pencil-line"></i>
                </a>
                {{-- Tombol Pemicu Modal "Delete" --}}
                <a class="btn btn-sm btn-icon btn-outline-danger" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#deleteBranchModal">
                  <i class="ri-delete-bin-line"></i>
                </a>
              </div>
            </td>
          </tr>
          {{-- Contoh Data Baris 4 --}}
          <tr>
            <td><strong>Branch D</strong></td>
            <td>Bantul</td>

            <td>
              <div class="d-flex">
                {{-- Tombol Pemicu Modal "Edit" --}}
                <a class="btn btn-sm btn-icon btn-outline-primary me-2" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#editBranchModal">
                  <i class="ri-pencil-line"></i>
                </a>
                {{-- Tombol Pemicu Modal "Delete" --}}
                <a class="btn btn-sm btn-icon btn-outline-danger" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#deleteBranchModal">
                  <i class="ri-delete-bin-line"></i>
                </a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- ===== Paginasi ===== -->
    <div class="card-footer d-flex justify-content-center">
      <nav aria-label="Page navigation">
        <ul class="pagination mb-0">
          <li class="page-item prev">
            <a class="page-link" href="javascript:void(0);"><i class="ri-arrow-left-s-line"></i></a>
          </li>
          <li class="page-item">
            <a class="page-link" href="javascript:void(0);">1</a>
          </li>
          <li class="page-item">
            <a class="page-link" href="javascript:void(0);">2</a>
          </li>
          <li class="page-item active">
            <a class="page-link" href="javascript:void(0);">3</a>
          </li>
          <li class="page-item">
            <a class="page-link" href="javascript:void(0);">4</a>
          </li>
          <li class="page-item">
            <a class="page-link" href="javascript:void(0);">5</a>
          </li>
          <li class="page-item next">
            <a class="page-link" href="javascript:void(0);"><i class="ri-arrow-right-s-line"></i></a>
          </li>
        </ul>
      </nav>
    </div>

  </div>


  <!-- ===== MODALS ===== -->

  <!-- 1. Modal Tambah Branch (Add Branch) -->
  <div class="modal fade" id="addBranchModal" tabindex="-1" aria-labelledby="addBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addBranchModalLabel">Add New Branch</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="addBranchName" class="form-label">Branch Name</label>
              <input type="text" class="form-control" id="addBranchName" placeholder="e.g., Branch Wonosari">
            </div>
            <div class="mb-3">
              <label for="addBranchLocations" class="form-label">Location</label>
              <input type="text" class="form-control" id="addBranchLocations" placeholder="e.g., Jl. Tentara Pelajar">
            </div>
            <div class="mb-3">
              <label for="addBranchDesc" class="form-label">Description (Optional)</label>
              <textarea class="form-control" id="addBranchDesc" rows="3"
                placeholder="Notes about this branch..."></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save Branch</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 2. Modal Edit Branch (Edit Branch) -->
  <div class="modal fade" id="editBranchModal" tabindex="-1" aria-labelledby="editBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editBranchModalLabel">Edit Branch</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            {{-- Di aplikasi nyata, Anda akan mengisi 'value' ini dengan data dari branch yang diklik --}}
            <div class="mb-3">
              <label for="editBranchName" class="form-label">Branch Name</label>
              <input type="text" class="form-control" id="editBranchName" value="Branch A">
            </div>
            <div class="mb-3">
              <label for="editBranchLocation" class="form-label">Location</label>
              <input type="text" class="form-control" id="editBranchLocation" value="Yogyakarta City">
            </div>
            <div class="mb-3">
              <label for="editBranchDesc" class="form-label">Description (Optional)</label>
              <textarea class="form-control" id="editBranchDesc" rows="3"></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Modal Hapus Branch (Delete Branch) -->
  <div class="modal fade" id="deleteBranchModal" tabindex="-1" aria-labelledby="deleteBranchModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteBranchModalLabel">Delete Branch?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this branch?</p>
          <p>This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </div>
  </div>

@endsection

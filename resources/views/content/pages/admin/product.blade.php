@extends('layouts.contentNavbarLayout')

{{-- Judul ini akan muncul di navbar atas Anda --}}
@section('title', 'Management Product')

@section('content')

  <div class="card">

    <!-- ===== Tombol Aksi (Action Bar) ===== -->
    <div class="card-header d-flex flex-column flex-md-row align-items-center justify-content-between">

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
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          {{-- Contoh Data Baris 1 --}}
          <tr>
            <td><strong>Chiffon Uk 15 Ori</strong></td>
            <td>A</td>
            <td>18.000</td>
            <td>14</td>
            <td>
              <div class="d-flex">
                {{-- Tombol Pemicu Modal "Edit" --}}
                <a class="btn btn-sm btn-icon btn-outline-primary me-2" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#editProductModal">
                  <i class="ri-pencil-line"></i>
                </a>
                {{-- Tombol Pemicu Modal "Delete" --}}
                <a class="btn btn-sm btn-icon btn-outline-danger" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#deleteProductModal">
                  <i class="ri-delete-bin-line"></i>
                </a>
              </div>
            </td>
          </tr>
          {{-- Contoh Data Baris 2 --}}
          <tr>
            <td><strong>Chiffon Uk 15 Standar</strong></td>
            <td>A</td>
            <td>18.000</td>
            <td>14</td>
            <td>
              <div class="d-flex">
                <a class="btn btn-sm btn-icon btn-outline-primary me-2" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#editProductModal">
                  <i class="ri-pencil-line"></i>
                </a>
                <a class="btn btn-sm btn-icon btn-outline-danger" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#deleteProductModal">
                  <i class="ri-delete-bin-line"></i>
                </a>
              </div>
            </td>
          </tr>
          {{-- Contoh Data Baris 3 --}}
          <tr>
            <td><strong>Bolu Kotak</strong></td>
            <td>A</td>
            <td>18.000</td>
            <td>14</td>
            <td>
              <div class="d-flex">
                <a class="btn btn-sm btn-icon btn-outline-primary me-2" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#editProductModal">
                  <i class="ri-pencil-line"></i>
                </a>
                <a class="btn btn-sm btn-icon btn-outline-danger" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#deleteProductModal">
                  <i class="ri-delete-bin-line"></i>
                </a>
              </div>
            </td>
          </tr>
          {{-- Contoh Data Baris 4 --}}
          <tr>
            <td><strong>Sisir Mentega</strong></td>
            <td>C</td>
            <td>18.000</td>
            <td>14</td>
            <td>
              <div class="d-flex">
                <a class="btn btn-sm btn-icon btn-outline-primary me-2" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#editProductModal">
                  <i class="ri-pencil-line"></i>
                </a>
                <a class="btn btn-sm btn-icon btn-outline-danger" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#deleteProductModal">
                  <i class="ri-delete-bin-line"></i>
                </a>
              </div>
            </td>
          </tr>
          {{-- Contoh Data Baris 5 --}}
          <tr>
            <td><strong>Krumpul Panjang Satu Rasa</strong></td>
            <td>C</td>
            <td>18.000</td>
            <td>14</td>
            <td>
              <div class="d-flex">
                <a class="btn btn-sm btn-icon btn-outline-primary me-2" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#editProductModal">
                  <i class="ri-pencil-line"></i>
                </a>
                <a class="btn btn-sm btn-icon btn-outline-danger" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#deleteProductModal">
                  <i class="ri-delete-bin-line"></i>
                </a>
              </div>
            </td>
          </tr>
          {{-- Contoh Data Baris 6 --}}
          <tr>
            <td><strong>Kepang Abon</strong></td>
            <td>C</td>
            <td>18.000</td>
            <td>14</td>
            <td>
              <div class="d-flex">
                <a class="btn btn-sm btn-icon btn-outline-primary me-2" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#editProductModal">
                  <i class="ri-pencil-line"></i>
                </a>
                <a class="btn btn-sm btn-icon btn-outline-danger" href="javascript:void(0);" data-bs-toggle="modal"
                  data-bs-target="#deleteProductModal">
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

  <!-- 1. Modal Tambah Produk (Add Product) -->
  <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label for="addProductName" class="form-label">Product Name</label>
              <input type="text" class="form-control" id="addProductName" placeholder="e.g., Chiffon Uk 20">
            </div>


            <div class="mb-3">
              <label for="addProductPrice" class="form-label">Price</label>
              <input type="number" class="form-control" id="addProductPrice" placeholder="e.g., 20000">
            </div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save Product</button>
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
          <form>
            <div class="mb-3">
              <label for="editProductName" class="form-label">Product Name</label>
              <input type="text" class="form-control" id="editProductName" placeholder="e.g., Chiffon Uk 20">
            </div>


            <div class="mb-3">
              <label for="editProductPrice" class="form-label">Price</label>
              <input type="number" class="form-control" id="editProductPrice" placeholder="e.g., 20000">
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
          <button type="button" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </div>
  </div>

@endsection

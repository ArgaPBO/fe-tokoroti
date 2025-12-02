@isset($pageConfigs)
  {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset
@php
  $configData = Helper::appClasses();
@endphp
@extends('layouts/commonMaster')

@php
  /* Display elements */
  $contentNavbar = ($contentNavbar ?? true);
  $containerNav = ($containerNav ?? 'container-xxl');
  $isNavbar = ($isNavbar ?? true);
  $isMenu = ($isMenu ?? true);
  $isFlex = ($isFlex ?? false);

  // --- KODE FINAL: Hanya tampilkan footer di halaman '/admin' ---
  // Secara default, sembunyikan footer
  $isFooter = false;
  // Kecuali jika URL adalah '/admin'
  if (request()->is('admin')) {
    $isFooter = true;
  }
  // Jika kamu juga ingin footer muncul di '/' (home), tambahkan:
  // elseif (request()->is('/')) {
  //     $isFooter = true;
  // }
  // --- AKHIR KODE FINAL ---

  $customizerHidden = ($customizerHidden ?? '');

  /* HTML Classes */
  $navbarDetached = 'navbar-detached';
  $menuFixed = (isset($configData['menuFixed']) ? $configData['menuFixed'] : '');
  if (isset($navbarType)) {
    $configData['navbarType'] = $navbarType;
  }
  $navbarType = (isset($configData['navbarType']) ? $configData['navbarType'] : '');
  $footerFixed = (isset($configData['footerFixed']) ? $configData['footerFixed'] : '');
  $menuCollapsed = (isset($configData['menuCollapsed']) ? $configData['menuCollapsed'] : '');

  /* Content classes */
  $container = ($configData['contentLayout'] === 'compact') ? 'container-xxl' : 'container-fluid';

@endphp

@section('layoutContent')
  <div class="layout-wrapper layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
    <div class="layout-container">

      @if ($isMenu)
        @include('layouts/sections/menu/verticalMenuBranch')
      @endif


      <!-- Layout page -->
      <div class="layout-page">

        {{-- Below commented code read by artisan command while installing jetstream. !! Do not remove if you want to use
        jetstream. --}}
        {{-- <x-banner /> --}}

        <!-- BEGIN: Navbar-->
        @if ($isNavbar)
          @include('layouts/sections/navbar/navbarBranch')
        @endif
        <!-- END: Navbar-->


        <!-- Content wrapper -->
        <div class="content-wrapper">

          <!-- Content -->
          @if ($isFlex)
            <div class="{{$container}} d-flex align-items-stretch flex-grow-1 p-0">
          @else
              <div class="{{$container}} flex-grow-1 container-p-y">
            @endif

              @yield('content')

            </div>
            <!-- / Content -->

            <!-- Footer -->
            @if ($isFooter)
              @include('layouts/sections/footer/footer')
            @endif
            <!-- / Footer -->
            <div class="content-backdrop fade"></div>
          </div>
          <!--/ Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      @if ($isMenu)
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
      @endif
      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

  <!-- Export LabaRugi Modal -->
  <div class="modal fade" id="exportLabaRugiModal" tabindex="-1" aria-labelledby="exportLabaRugiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exportLabaRugiModalLabel">Export Laba/Rugi Report</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="exportLabaRugiForm" method="GET" action="/branch/export/labarugi">
          <div class="modal-body">
            <div class="mb-3">
              <label for="exportStartDate" class="form-label">Start Date <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="exportStartDate" name="start_date" required />
            </div>
            <div class="mb-3">
              <label for="exportEndDate" class="form-label">End Date <span class="text-danger">*</span></label>
              <input type="date" class="form-control" id="exportEndDate" name="end_date" required />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Export</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function getFirstDayOfMonth() {
      const now = new Date();
      return new Date(now.getFullYear(), now.getMonth(), 1);
    }

    function getLastDayOfMonth() {
      const now = new Date();
      return new Date(now.getFullYear(), now.getMonth() + 1, 0);
    }

    function formatDateForInput(date) {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
    }

    function openExportModal() {
      const startDate = getFirstDayOfMonth();
      const endDate = getLastDayOfMonth();
      document.getElementById('exportStartDate').value = formatDateForInput(startDate);
      document.getElementById('exportEndDate').value = formatDateForInput(endDate);
      const modal = new bootstrap.Modal(document.getElementById('exportLabaRugiModal'));
      modal.show();
    }

    // Wire exportlabarugilogo button to open modal
    document.addEventListener('DOMContentLoaded', () => {
      const exportBtn = document.querySelector('.exportlabarugilogo').closest('a');

      exportBtn.removeAttribute('href'); // disable redirect
      exportBtn.style.cursor = 'pointer'; // optional, keeps pointer indicator
      if (exportBtn) {
        exportBtn.addEventListener('click', (e) => {
          e.preventDefault();
          openExportModal();
        });
      }
    });
  </script>

@endsection

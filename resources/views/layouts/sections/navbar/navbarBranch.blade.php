@php
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\Route;
  $containerNav = ($configData['contentLayout'] === 'compact') ? 'container-xxl' : 'container-fluid';
  $navbarDetached = ($navbarDetached ?? '');

  // --- Bagian Baru: Ambil Nama Halaman dari JSON ---
  $currentPath = trim(request()->path(), '/'); // Ambil path dan hapus slash di awal/akhir
  $pageTitle = ''; // Default

  $menuJsonPath = resource_path('menu/verticalMenu.json'); // <- Ganti path disini
  if (file_exists($menuJsonPath)) {
    $menuData = json_decode(file_get_contents($menuJsonPath), true);

    if ($menuData && isset($menuData['menu'])) {
      foreach ($menuData['menu'] as $item) {
        // Cek item utama
        if (isset($item['url'])) {
          $itemUrl = trim($item['url'], '/'); // Hapus slash di awal/akhir
          if ($currentPath === $itemUrl) {
            $pageTitle = $item['name'] ?? 'Unknown Page';
            break;
          }
        }

        // Cek submenu
        if (isset($item['submenu']) && is_array($item['submenu'])) {
          foreach ($item['submenu'] as $subItem) {
            if (isset($subItem['url'])) {
              $subUrl = trim($subItem['url'], '/');
              if ($currentPath === $subUrl) {
                $pageTitle = $subItem['name'] ?? 'Unknown Subpage';
                break 2; // Keluar dari dua loop
              }
            }
          }
        }
      }
    }
  }
  // --- Akhir Bagian Baru ---
@endphp

<!-- Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
  <nav
    class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme"
    id="layout-navbar">
@endif
  @if(isset($navbarDetached) && $navbarDetached == '')
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
      <div class="{{$containerNav}}">
  @endif

      <!-- ! Not required for layout-without-menu -->
      @if(!isset($navbarHideToggle))
        <div
          class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
          <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="ri-menu-fill ri-24px"></i>
          </a>
        </div>
      @endif

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        <!-- JUDUL HALAMAN DINAMIS BERDASARKAN JSON -->
        <h1 class="nav-item nav-link mb-0 me-3 p-0">{{ $pageTitle }}</h1>

        <ul class="navbar-nav flex-row align-items-center ms-auto">


          <!--/ Search -->

          <!-- TOMBOL NOTIFIKASI -->
          
          <!--/ Notification -->

          <!-- LOGO PENGGUNA (KODE ASLI ANDA) -->
          <h1 class="nav-item nav-link mb-0 me-3 p-0" id="userDisplay">Branch - Loading...</h1>
          <div class="d-grid px-4 pt-2 pb-1">
                  <button class="btn btn-sm btn-danger d-flex" id="logoutBtn" onclick="handleLogout(event);">
                    <small class="align-middle">Logout</small>
                    <i class="ri-logout-box-r-line ms-2 ri-16px"></i>
                  </button>
                </div>
          <!--/ User -->
        </ul>
      </div>
      @if(!isset($navbarDetached))
        </div>
      @endif
  </nav>

  <script>
    let branchName1 = 'Branch';
    function getCookie(name) {
      const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
      return match ? decodeURIComponent(match[2]) : null;
    }

    function deleteCookie(name) {
      document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
    }

    async function fetchUserData() {
      try {
        const apiUrl = '{{ env("API_URL") }}';
        const token = getCookie('token');
        const res = await fetch(`${apiUrl}/branch`, {
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
          },
          credentials: 'include'
        });
        if (res.status === 403) {
          deleteCookie('token');
          deleteCookie('is_admin');
          window.location.href = '/login';
          return;
        }
        if (!res.ok) throw new Error('Failed to fetch');
        const data = await res.json();
        const branchName = data.branch?.name || 'Branch';
        const userName = data.user?.name || 'Unknown';
        document.getElementById('userDisplay').textContent = `${branchName} - ${userName}`;
        branchName1 = branchName;
      } catch (err) {
        console.error('Error fetching user:', err);
        document.getElementById('userDisplay').textContent = 'Branch - Error';
      }
    }

    async function handleLogout(event) {
      event.preventDefault();
      const apiUrl = '{{ env("API_URL") }}';
      const token = getCookie('token');
      
      if (!token) {
        // no token, just redirect to login
        window.location.href = '/login';
        return;
      }

      try {
        const res = await fetch(`${apiUrl}/logout`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
          }
        });

        // clear cookies regardless of response
        deleteCookie('token');
        deleteCookie('is_admin');

        // redirect to login page
        window.location.href = '/login';

      } catch (err) {
        console.error('Logout error:', err);
        // clear cookies and redirect anyway
        deleteCookie('token');
        deleteCookie('is_admin');
        window.location.href = '/login';
      }
    }

    document.addEventListener('DOMContentLoaded', fetchUserData);
  </script>
  <!-- / Navbar -->

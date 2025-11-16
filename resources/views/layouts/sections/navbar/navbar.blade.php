@php
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\Route;
  $containerNav = ($configData['contentLayout'] === 'compact') ? 'container-xxl' : 'container-fluid';
  $navbarDetached = ($navbarDetached ?? '');

  // --- Bagian Baru: Ambil Nama Halaman dari JSON ---
  $currentPath = trim(request()->path(), '/'); // Ambil path dan hapus slash di awal/akhir
  $pageTitle = 'Dashboard'; // Default

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
          <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 me-xl-1">
            <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
              href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
              <i class="ri-notification-3-line ri-22px"></i>
              <span class="badge bg-danger rounded-pill badge-notifications">5</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end py-0">
              <li class="dropdown-menu-header border-bottom">
                <div class="dropdown-header d-flex align-items-center py-3">
                  <h6 class="mb-0 me-auto">Notifications</h6>
                  <span class="badge rounded-pill bg-label-primary">5 New</span>
                </div>
              </li>
              <li class="dropdown-notifications-list scrollable-container">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item list-group-item-action dropdown-notifications-item">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar">
                          <img src="{{ asset('assets/img/avatars/1.png') }}" alt class="rounded-circle"
                            onerror="this.src='https://placehold.co/40x40/666666/FFFFFF?text=A'">
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-1">Congratulation Sam! 🥳</h6>
                        <p class="mb-0">Won the monthly best seller badge</p>
                        <small class="text-muted">1h ago</small>
                      </div>
                      <div class="flex-shrink-0 dropdown-notifications-actions">
                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><i
                            class="ri-close-line ri-20px"></i></a>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item list-group-item-action dropdown-notifications-item">
                    <div class="d-flex">
                      <div class="flex-shrink-0 me-3">
                        <div class="avatar">
                          <span class="avatar-initial rounded-circle bg-label-danger">CF</span>
                        </div>
                      </div>
                      <div class="flex-grow-1">
                        <h6 class="mb-1">Charles Franklin</h6>
                        <p class="mb-0">Accepted your connection</p>
                        <small class="text-muted">12hr ago</small>
                      </div>
                      <div class="flex-shrink-0 dropdown-notifications-actions">
                        <a href="javascript:void(0)" class="dropdown-notifications-archive"><i
                            class="ri-close-line ri-20px"></i></a>
                      </div>
                    </div>
                  </li>
                  {{-- Anda bisa tambahkan item notifikasi lain di sini --}}
                </ul>
              </li>
              <li class="dropdown-menu-footer border-top">
                <a href="javascript:void(0);" class="dropdown-item d-flex justify-content-center p-3">
                  View all notifications
                </a>
              </li>
            </ul>
          </li>
          <!--/ Notification -->

          <!-- LOGO PENGGUNA (KODE ASLI ANDA) -->
          <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
              <div class="avatar avatar-online">
                <img src="{{ Auth::user() ? Auth::user()->profile_photo_url : asset('assets/img/avatars/1.png') }}" alt
                  class="rounded-circle" onerror="this.src='https://placehold.co/40x40/666666/FFFFFF?text=U'">
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item"
                  href="{{ Route::has('profile.show') ? route('profile.show') : 'javascript:void(0);' }}">
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-2">
                      <div class="avatar avatar-online">
                        <img
                          src="{{ Auth::user() ? Auth::user()->profile_photo_url : asset('assets/img/avatars/1.png') }}"
                          alt class="rounded-circle"
                          onerror="this.src='https://placehold.co/40x40/666666/FFFFFF?text=U'">
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <span class="fw-medium d-block small">
                        @if (Auth::check())
                          {{ Auth::user()->name }}
                        @else
                          John Doe
                        @endif
                      </span>
                      <small class="text-muted">Administrator</small>
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <div class="dropdown-divider"></div>
              </li>
              <li>
                <a class="dropdown-item"
                  href="{{ Route::has('profile.show') ? route('profile.show') : 'javascript:void(0);' }}">
                  <i class="ri-user-3-line ri-22px me-3"></i><span class="align-middle">My Profile</span>
                </a>
              </li>
              @if (Auth::check() && Laravel\Jetstream\Jetstream::hasApiFeatures())
                <li>
                  <a class="dropdown-item" href="{{ route('api-tokens.index') }}">
                    <i class="ri-key-2-line ri-22px me-3"></i><span class="align-middle">API Tokens</span>
                  </a>
                </li>
              @endif
              <li>
                <a class="dropdown-item" href="javascript:void(0);">
                  <span class="d-flex align-items-center align-middle">
                    <i class="flex-shrink-0 ri-file-text-line ri-22px me-3"></i>
                    <span class="flex-grow-1 align-middle">Billing</span>
                  </span>
                </a>
              </li>

              @if (Auth::User() && Laravel\Jetstream\Jetstream::hasTeamFeatures())
                <li>
                  <div class="dropdown-divider"></div>
                </li>
                <li>
                  <h6 class="dropdown-header">Manage Team</h6>
                </li>
                <li>
                  <div class="dropdown-divider"></div>
                </li>
                <li>
                  <a class="dropdown-item"
                    href="{{ Auth::user() ? route('teams.show', Auth::user()->currentTeam->id) : 'javascript:void(0)' }}">
                    <i class="ri-settings-3-line ri-22px me-3"></i><span class="align-middle">Team Settings</span>
                  </a>
                </li>
                @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                  <li>
                    <a class="dropdown-item" href="{{ route('teams.create') }}">
                      <i class="ri-group-line ri-22px me-3"></i><span class="align-middle">Create New Team</span>
                    </a>
                  </li>
                @endcan
                @if (Auth::user()->allTeams()->count() > 1)
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <h6 class="dropdown-header">Switch Teams</h6>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                @endif

                @if (Auth::user())
                  @foreach (Auth::user()->allTeams() as $team)
                    {{-- Below commented code read by artisan command while installing jetstream. !! Do not remove if you want
                    to use jetstream. --}}

                    {{-- <x-switchable-team :team="$team" /> --}}
                  @endforeach
                @endif
              @endif
              <li>
                <div class="dropdown-divider"></div>
              </li>
              <li>
                <div class="d-grid px-4 pt-2 pb-1">
                  <button class="btn btn-sm btn-danger d-flex" id="logoutBtn" onclick="handleLogout(event);">
                    <small class="align-middle">Logout</small>
                    <i class="ri-logout-box-r-line ms-2 ri-16px"></i>
                  </button>
                </div>
              </li>
            </ul>
          </li>
          <!--/ User -->
        </ul>
      </div>
      @if(!isset($navbarDetached))
        </div>
      @endif
  </nav>

  <script>
    function getCookie(name) {
      const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
      return match ? decodeURIComponent(match[2]) : null;
    }

    function deleteCookie(name) {
      document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
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
  </script>
  <!-- / Navbar -->

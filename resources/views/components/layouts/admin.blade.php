<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin - Unmasker</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Favicons -->
  <link href="/assets/img/favicon.png" rel="icon">
  <link href="/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="/assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="/assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="/assets/css/style.css" rel="stylesheet">
  
  <!-- Livewire Styles -->
  @livewireStyles
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="{{ route('admin.dashboard') }}" class="logo d-flex align-items-center">
        <span class="d-none d-lg-block">Unmasker🎭</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <!-- Search Bar for Desktop -->
        <li class="nav-item d-none d-md-block">
          <div class="search-bar-container position-relative">
            <button class="nav-link nav-icon search-bar-toggle bg-transparent border-0" 
                    type="button" 
                    data-bs-toggle="collapse" 
                    data-bs-target="#searchBarCollapse"
                    aria-expanded="false" 
                    aria-controls="searchBarCollapse">
              <i class="bi bi-search"></i>
            </button>
            
            <div class="collapse position-absolute search-dropdown" id="searchBarCollapse" 
                 style="top: 100%; right: 0; width: 350px; z-index: 1000;">
              <div class="card shadow-lg border-0">
                <div class="card-body p-3">
                  <!-- Livewire Search Component -->
                  @livewire('admin.user-search')
                </div>
              </div>
            </div>
          </div>
        </li><!-- End Search Bar -->

        <!-- Mobile Search Icon -->
        <li class="nav-item d-block d-md-none">
          <a class="nav-link nav-icon search-bar-toggle" href="#" data-bs-toggle="modal" data-bs-target="#searchModal">
            <i class="bi bi-search"></i>
          </a>
        </li><!-- End Mobile Search Icon-->

        <!-- 🔥 NOTIFICATION BELL - ADMIN -->
        <li class="nav-item dropdown">
          <livewire:user.notification-bell />
        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            @php
                $user = Auth::user();
                $avatarUrl = $user->avatar_url ?? '/assets/img/default-avatar.png';
            @endphp
            <img src="{{ $avatarUrl }}" alt="Profile" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ $user->name ?? 'Admin' }}</span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{ Auth::user()->name ?? 'Admin' }}</h6>
              <span>Administrator</span>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.profile') }}">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            
            <!-- Friends Menu Item -->
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.friends') }}">
                <i class="bi bi-people"></i>
                <span>My Friends</span>
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
           
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>
            </li>
          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <li class="nav-item">
        <a wire:navigate class="nav-link {{ request()->routeIs('admin.dashboard') ? '' : 'collapsed' }}" href="{{ route('admin.dashboard') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <li class="nav-item">
        <a wire:navigate class="nav-link {{ request()->routeIs('admin.leaderboard') ? '' : 'collapsed' }}" href="{{ route('admin.leaderboard') }}">
          <i class="bi bi-bar-chart"></i>
          <span>Leaderboard</span>
        </a>
      </li>

      <li class="nav-item">
        <a wire:navigate class="nav-link {{ request()->routeIs('admin.quizz') ? '' : 'collapsed' }}" href="{{ route('admin.quizz') }}">
          <i class="bi bi-question-circle"></i>
          <span>Quizz</span>
        </a>
      </li>

      <li class="nav-item">
        <a wire:navigate class="nav-link {{ request()->routeIs('admin.literacy') ? '' : 'collapsed' }}" href="{{ route('admin.literacy') }}">
          <i class="bi bi-book"></i>
          <span>Literacy</span>
        </a>
      </li>

      <!-- Friends Sidebar Item -->
      <li class="nav-item">
        <a wire:navigate class="nav-link {{ request()->routeIs('admin.friends') ? '' : 'collapsed' }}" href="{{ route('admin.friends') }}">
          <i class="bi bi-people"></i>
          <span>Friends</span>
          @auth
            @php
                $pendingCount = auth()->user()->friendRequests()->count();
            @endphp
            @if($pendingCount > 0)
                <span class="badge bg-danger rounded-pill ms-auto">
                    {{ $pendingCount }}
                </span>
            @endif
          @endauth
        </a>
      </li>

      <li class="nav-item">
        <a wire:navigate class="nav-link {{ request()->routeIs('admin.about') ? '' : 'collapsed' }}" href="{{ route('admin.about') }}">
          <i class="bi bi-info-circle"></i>
          <span>About</span>
        </a>
      </li>
    </ul>
  </aside><!-- End Sidebar-->

  <!-- ======= Main Content ======= -->
  <main id="main" class="main">
    <div class="p-4">
      <!-- Menambahkan wrapper untuk Livewire components -->
      <div>
        {{ $slot }}
      </div>
    </div>
  </main><!-- End #main -->

  <!-- Search Modal for Mobile -->
  <div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Search Users</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @livewire('admin.user-search')
        </div>
      </div>
    </div>
  </div>

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Unmasker</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      Design by Sirhan
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="/assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/vendor/chart.js/chart.min.js"></script>
  <script src="/assets/vendor/echarts/echarts.min.js"></script>
  <script src="/assets/vendor/quill/quill.min.js"></script>
  <script src="/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="/assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="/assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="/assets/js/main.js"></script>

  <!-- Livewire Scripts -->
  @livewireScripts

  <style>
    .search-dropdown {
      min-width: 350px;
    }
    
    .search-bar-container .nav-icon {
      color: #6c757d;
      transition: color 0.3s ease;
      padding: 8px 12px;
      border-radius: 50%;
    }
    
    .search-bar-container .nav-icon:hover {
      color: #4154f1;
      background-color: rgba(65, 84, 241, 0.1);
    }
    
    .friend-result-item {
      transition: background-color 0.2s ease;
      cursor: pointer;
      border-radius: 8px;
    }
    
    .friend-result-item:hover {
      background-color: #f8f9fa;
    }

    /* Mobile search modal styling */
    #searchModal .modal-body {
      padding: 0;
    }

    /* Ensure search results are visible */
    .search-results {
      max-height: 400px;
      overflow-y: auto;
    }

    /* Notification bell custom styling */
    .notification-bell .btn {
      background: transparent !important;
      border: none !important;
      color: #6c757d !important;
      padding: 8px 12px;
    }

    .notification-bell .btn:hover {
      color: #4154f1 !important;
      background-color: rgba(65, 84, 241, 0.1) !important;
    }
  </style>

  <script>
    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
      // Close search dropdown when clicking outside (Desktop)
      document.addEventListener('click', function(event) {
        const searchContainer = document.querySelector('.search-bar-container');
        const searchDropdown = document.getElementById('searchBarCollapse');
        
        if (searchContainer && searchDropdown && !searchContainer.contains(event.target) && searchDropdown.classList.contains('show')) {
          const bsCollapse = new bootstrap.Collapse(searchDropdown, {
            toggle: false
          });
          bsCollapse.hide();
        }
      });

      // Auto-focus search input when dropdown opens (Desktop)
      const searchDropdown = document.getElementById('searchBarCollapse');
      if (searchDropdown) {
        searchDropdown.addEventListener('shown.bs.collapse', function() {
          const searchInput = this.querySelector('input[type="search"]');
          if (searchInput) {
            setTimeout(() => {
              searchInput.focus();
            }, 100);
          }
        });
      }

      // Auto-focus search input when modal opens (Mobile)
      const searchModal = document.getElementById('searchModal');
      if (searchModal) {
        searchModal.addEventListener('shown.bs.modal', function() {
          const searchInput = this.querySelector('input[type="search"]');
          if (searchInput) {
            setTimeout(() => {
              searchInput.focus();
            }, 100);
          }
        });
      }

      // Close mobile modal when clicking on search result actions
      document.addEventListener('livewire:action', function() {
        const searchModal = bootstrap.Modal.getInstance(document.getElementById('searchModal'));
        if (searchModal) {
          // Don't close modal immediately, let user see the success message
          setTimeout(() => {
            searchModal.hide();
          }, 1500);
        }
      });

      // Existing avatar selection functionality
      const avatarOptions = document.querySelectorAll('.avatar-option');
      avatarOptions.forEach(option => {
        option.addEventListener('click', function() {
          avatarOptions.forEach(opt => opt.classList.remove('selected'));
          this.classList.add('selected');
          
          const preview = document.getElementById('avatarPreview');
          if (preview) {
            preview.src = this.src;
          }
        });
      });
      
      // File upload preview
      const fileInput = document.getElementById('avatarUpload');
      if (fileInput) {
        fileInput.addEventListener('change', function() {
          if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
              const preview = document.getElementById('avatarPreview');
              if (preview) {
                preview.src = e.target.result;
              }
            }
            reader.readAsDataURL(this.files[0]);
          }
        });
      }

      // Livewire navigation handling
      document.addEventListener('livewire:navigated', () => {
        if (typeof ApexCharts !== 'undefined') {
          ApexCharts.exec('reinit');
        }
      });
    });
  </script>

</body>

</html>
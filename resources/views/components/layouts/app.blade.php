<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Unmasker - User Dashboard</title>
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

  <style>
    /* Custom Styles untuk Profile */
    .avatar-container {
      position: relative;
      display: inline-block;
    }
    
    .nav-profile img {
        border-radius: 50% !important;
        width: 36px;
        height: 36px;
        object-fit: cover;
    }

    /* Pastikan juga di header */
    .header .nav-profile img {
        border-radius: 50%;
    }
    
    .avatar-preview {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #f0f0f0;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .avatar-options {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-top: 20px;
      max-height: 300px;
      overflow-y: auto;
      padding: 10px;
    }
    
    .avatar-option {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      cursor: pointer;
      border: 3px solid transparent;
      transition: all 0.3s ease;
    }
    
    .avatar-option:hover {
      transform: scale(1.1);
      border-color: #4154f1;
    }
    
    .avatar-option.selected {
      border-color: #4154f1;
      box-shadow: 0 0 0 3px rgba(65, 84, 241, 0.3);
    }
    
    .profile-card {
      border-radius: 15px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.08);
      border: none;
      overflow: hidden;
      background: white;
    }
    
    .profile-header {
      background: linear-gradient(135deg, #4154f1 0%, #717ff8 100%);
      color: white;
      padding: 40px 30px;
      text-align: center;
      position: relative;
    }
    
    .profile-body {
      padding: 40px;
    }
    
    .form-section {
      margin-bottom: 35px;
      padding-bottom: 25px;
      border-bottom: 1px solid #f0f0f0;
    }
    
    .form-section:last-child {
      border-bottom: none;
    }
    
    .section-title {
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 25px;
      color: #4154f1;
      display: flex;
      align-items: center;
      padding-bottom: 10px;
      border-bottom: 2px solid #f0f0f0;
    }
    
    .section-title i {
      margin-right: 12px;
      font-size: 1.5rem;
    }

    /* Search bar styling */
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
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="{{ route('user.dashboard') }}" class="logo d-flex align-items-center">
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

        <!-- 🔥 NOTIFICATION BELL - USER -->
        <li class="nav-item dropdown">
          <livewire:user.notification-bell />
        </li><!-- End Notification Nav -->

        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{ Auth::user()->avatar_url ?? '/assets/img/profile-img.jpg' }}" alt="Profile" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{ Auth::user()->name }}</h6>
              <span>Member</span>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('user.profile') }}">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('user.friends') }}">
                <i class="bi bi-people"></i>
                <span>My Friends</span>
              </a>
            </li>
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
        <a wire:navigate class="nav-link {{ request()->routeIs('user.dashboard') ? '' : 'collapsed' }}" href="{{ route('user.profile') }}">
          <i class="bi bi-grid"></i>
          <span>My Profile</span>
        </a>
      </li>

      <li class="nav-item">
        <a wire:navigate class="nav-link {{ request()->routeIs('user.dashboard') ? '' : 'collapsed' }}" href="{{ route('user.dashboard') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <li class="nav-item">
        <a wire:navigate class="nav-link {{ request()->routeIs('user.leaderboard') ? '' : 'collapsed' }}" href="{{ route('user.leaderboard') }}">
          <i class="bi bi-bar-chart"></i>
          <span>Leaderboard</span>
        </a>
      </li>

      <!-- Friends Menu Item -->
      <li class="nav-item">
        <a wire:navigate class="nav-link {{ request()->routeIs('user.friends') ? '' : 'collapsed' }}" href="{{ route('user.friends') }}">
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
        <a wire:navigate class="nav-link {{ request()->routeIs('user.quizz') ? '' : 'collapsed' }}" href="{{ route('user.quizz') }}">
          <i class="bi bi-question-circle"></i>
          <span>Quizz</span>
        </a>
      </li>

      <li class="nav-item">
        <a wire:navigate class="nav-link {{ request()->routeIs('user.literacy') ? '' : 'collapsed' }}" href="{{ route('user.literacy') }}">
          <i class="bi bi-book"></i>
          <span>Literacy</span>
        </a>
      </li>

      <li class="nav-item">
        <a wire:navigate class="nav-link {{ request()->routeIs('user.about') ? '' : 'collapsed' }}" href="{{ route('user.about') }}">
          <i class="bi bi-info-circle"></i>
          <span>About</span>
        </a>
      </li>
    </ul>
  </aside><!-- End Sidebar-->

  <!-- ======= Main Content ======= -->
  <main id="main" class="main">
    <div class="p-4">
      {{ $slot }}
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

      // Avatar selection functionality
      const avatarOptions = document.querySelectorAll('.avatar-option');
      avatarOptions.forEach(option => {
        option.addEventListener('click', function() {
          avatarOptions.forEach(opt => opt.classList.remove('selected'));
          this.classList.add('selected');
          
          // Update preview if exists
          const preview = document.getElementById('avatarPreview');
          if (preview) {
            preview.src = this.src;
          }
        });
      });
    });
  </script>

</body>

</html>
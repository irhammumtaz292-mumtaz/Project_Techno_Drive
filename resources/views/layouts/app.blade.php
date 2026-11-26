<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Dashboard') | PT Techno Drive Engineering</title>
    <meta name="description" content="Sistem Informasi Manajemen Proyek - PT Techno Drive Engineering">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo_TD.png') }}?v={{ time() }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/logo_TD.png') }}?v={{ time() }}">
    <!-- Google Fonts: Plus Jakarta Sans (Heading/Logo) + Inter (Body/UI) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.8 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons 1.13.1 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- Nalika Icon CSS -->
    <link rel="stylesheet" href="{{ asset('nalika/css/nalika-icon.css') }}">
    <!-- Swiper 12 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css">
    <!-- AOS 2.3.4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">
    <!-- SimpleBar 6.2.7 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplebar@6.2.7/dist/simplebar.min.css">

    <!-- Nalika Main Template CSS -->
    <link rel="stylesheet" href="{{ asset('nalika/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('nalika/style.css') }}">
    <link rel="stylesheet" href="{{ asset('nalika/css/responsive.css') }}">

    <!-- PT Techno Drive Brand Theme Overrides (Dominan Putih, Sidebar Hitam, Aksen Oren) -->
    <link rel="stylesheet" href="{{ asset('nalika/css/techno-drive-theme.css') }}?v={{ time() }}">

    @stack('styles')
</head>

<body>

    @auth
    <!-- ==============================================================
         SIDEBAR AREA (HITAM #111111)
         ============================================================== -->
    <div class="left-sidebar-pro">
        <nav id="sidebar">
            <!-- Sidebar Header & Brand Logo -->
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2">
                    <span class="main-logo" style="font-size: 16px; font-weight: 800; color: #FFFFFF; letter-spacing: 1px; font-family: 'Plus Jakarta Sans', sans-serif;">
                        TECHNO<span style="color: #FF5500;">DRIVE</span>
                    </span>
                </a>
                <strong style="font-size: 13px; color: #FF5500; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 800;">TD</strong>
            </div>

            <!-- Profile Info Widget -->
            <div class="nalika-profile">
                <div class="profile-dtl">
                    <a href="#">
                        <img src="{{ asset('img/logo_TD.png') }}" alt="{{ Auth::user()->name }}" />
                    </a>
                    <h2>{{ Auth::user()->name }}</h2>
                    <div>
                        <span class="role-badge">{{ Auth::user()->role }}</span>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="left-custom-menu-adp-wrap">
                <nav class="sidebar-nav left-sidebar-menu-pro">
                    <ul class="metismenu" id="menu1">

                        <!-- 01: Dashboard -->
                        <li class="{{ request()->routeIs('dashboard') ? 'active mm-active' : '' }}">
                            <a href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i>
                                <span class="mini-click-non">Dashboard</span>
                            </a>
                        </li>

                        <!-- 02: Kelola Proyek -->
                        <li class="{{ request()->routeIs('projects.index', 'projects.show', 'projects.create', 'projects.store', 'projects.download-po') ? 'active mm-active' : '' }}">
                            <a href="{{ route('projects.index') }}">
                                <i class="bi bi-folder2-open"></i>
                                <span class="mini-click-non">Kelola Proyek</span>
                            </a>
                        </li>

                        @php
                            $userRoleNorm = strtolower(trim(Auth::user()->role ?? ''));
                        @endphp

                        @if(in_array($userRoleNorm, ['supervisor']))
                        <!-- 03: Master Data (Customer, End User, Staff) -->
                        @php
                            $isMasterActive = request()->routeIs('customers.*', 'end_users.*', 'staffs.*');
                        @endphp
                        <li class="{{ $isMasterActive ? 'active mm-active' : '' }}">
                            <a class="has-arrow" href="javascript:void(0);" aria-expanded="{{ $isMasterActive ? 'true' : 'false' }}">
                                <i class="bi bi-database"></i>
                                <span class="mini-click-non">Master Data</span>
                            </a>
                            <ul class="submenu-angle" style="{{ $isMasterActive ? 'display:block;' : '' }}">
                                <li class="{{ request()->routeIs('customers.*') ? 'active' : '' }}">
                                    <a title="Data Customer" href="{{ route('customers.index') }}">
                                        <span class="mini-sub-pro"><i class="bi bi-building me-1" style="font-size:11px;"></i> Customer</span>
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('end_users.*') ? 'active' : '' }}">
                                    <a title="Data End User" href="{{ route('end_users.index') }}">
                                        <span class="mini-sub-pro"><i class="bi bi-person-badge me-1" style="font-size:11px;"></i> End User</span>
                                    </a>
                                </li>
                                <li class="{{ request()->routeIs('staffs.*') ? 'active' : '' }}">
                                    <a title="Data Staff" href="{{ route('staffs.index') }}">
                                        <span class="mini-sub-pro"><i class="bi bi-people me-1" style="font-size:11px;"></i> Data Staff</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif

                        @if(in_array($userRoleNorm, ['supervisor', 'logistics', 'logistik', 'finance', 'keuangan', 'director', 'direktur']))
                        <!-- Verifikasi & Manajemen SPK -->
                        <li class="{{ request()->routeIs('spks.*') ? 'active mm-active' : '' }}">
                            <a href="{{ route('spks.index') }}">
                                <i class="bi bi-file-earmark-check"></i>
                                <span class="mini-click-non">Verifikasi SPK</span>
                            </a>
                        </li>
                        @endif

                        @if(in_array($userRoleNorm, ['finance', 'keuangan']))
                        <!-- Finance Specialized View -->
                        <li class="{{ request()->routeIs('payment.*') ? 'active mm-active' : '' }}">
                            <a href="{{ route('payment.index') }}">
                                <i class="bi bi-currency-dollar"></i>
                                <span class="mini-click-non">Verifikasi Bayar</span>
                            </a>
                        </li>
                        @endif

                        @if(in_array($userRoleNorm, ['logistics', 'logistik']))
                        <!-- Logistics Specialized View -->
                        <li class="{{ request()->routeIs('equipments.*', 'tools.*') ? 'active mm-active' : '' }}">
                            <a href="{{ route('equipments.index') }}">
                                <i class="bi bi-box-seam"></i>
                                <span class="mini-click-non">Inventori Alat</span>
                            </a>
                        </li>
                        @endif

                        <!-- Logout Button -->
                        <li>
                            <a href="javascript:void(0);" onclick="document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-left" style="color: #EF4444 !important;"></i>
                                <span class="mini-click-non" style="color: #EF4444;">Keluar</span>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>
        </nav>
    </div>

    <!-- ==============================================================
         MAIN CONTENT WRAPPER (DOMINAN PUTIH #FFFFFF / #F8F9FA)
         ============================================================== -->
    <div class="all-content-wrapper">

        <!-- Top Navigation Header Bar (PUTIH) -->
        <div class="header-advance-area">
            <div class="header-top-area">
                <div class="container-fluid">
                    <div class="nk-header-bar">
                        <!-- Sidebar Toggle Button -->
                        <button type="button" id="sidebarCollapse" class="me-3" title="Toggle Navigasi">
                            <i class="bi bi-list"></i>
                        </button>

                        <!-- Search Bar -->
                        <div class="nk-header-search d-none d-lg-block">
                            <input type="text" class="nk-search-input" placeholder="Cari proyek, No. PO, customer...">
                            <i class="bi bi-search nk-search-icon"></i>
                        </div>

                        <!-- Right Control Icons -->
                        <div class="nk-header-right ms-auto d-flex align-items-center gap-3">
                            <!-- System Status Indicator -->
                            <div class="d-none d-md-flex align-items-center me-3 font-mono-tech" style="font-size: 11px; color: #6B7280;">
                                <span class="badge bg-success rounded-circle p-1 me-1" style="width:7px; height:7px;"></span>
                                <span>ONLINE (SYSTEM AUDITED)</span>
                            </div>

                            <!-- Notifications Dropdown -->
                            <div class="dropdown me-1">
                                <a href="#" class="nk-header-icon" data-bs-toggle="dropdown" aria-expanded="false" title="Notifikasi">
                                    <i class="bi bi-bell"></i>
                                    <span class="nk-badge"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <h6 class="nk-dd-title">Notifikasi Sistem</h6>
                                    <div class="nk-dd-body">
                                        <a href="{{ route('projects.index') }}" class="nk-dd-item">
                                            <div class="me-2 text-warning"><i class="bi bi-clock-history fs-5"></i></div>
                                            <div>
                                                <h6>Pengingat Deadline Final Report</h6>
                                                <p>Pastikan laporan teknis diunggah tepat waktu.</p>
                                            </div>
                                        </a>
                                        <a href="{{ route('projects.index') }}" class="nk-dd-item">
                                            <div class="me-2 text-primary"><i class="bi bi-file-earmark-check fs-5"></i></div>
                                            <div>
                                                <h6>Verifikasi Purchase Order (PO)</h6>
                                                <p>Periksa data pembayaran proyek terkini.</p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="nk-dd-footer">
                                        <a href="{{ route('projects.index') }}">Buka Daftar Proyek</a>
                                    </div>
                                </div>
                            </div>

                            <!-- User Profile Menu -->
                            <div class="dropdown">
                                <a href="#" class="nk-header-user-btn d-flex align-items-center text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset('nalika/img/notification/5.svg') }}" class="rounded-circle" style="width:28px; height:28px; border:1px solid #E5E7EB;" alt="avatar">
                                    <span class="nk-user-name d-none d-md-inline ms-1 me-1">{{ Auth::user()->name }}</span>
                                    <i class="bi bi-chevron-down" style="font-size: 10px; color: #6B7280;"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li class="px-3 py-2 border-bottom">
                                        <span class="d-block fw-bold" style="font-size: 13px; color: #111111;">{{ Auth::user()->name }}</span>
                                        <span class="font-mono-tech" style="font-size: 11px; color: #FF5500;">{{ strtoupper(Auth::user()->role) }}</span>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('projects.index') }}">
                                            <i class="bi bi-folder2 me-2"></i>Kelola Proyek
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a class="dropdown-item py-2 text-danger fw-semibold" href="javascript:void(0);" onclick="document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i>Keluar dari Sistem
                                        </a>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breadcome Area -->
        <div class="breadcome-area">
            <div class="container-fluid">
                <div class="breadcome-list">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="breadcomb-wp">
                                <div class="breadcomb-icon">
                                    <i class="bi bi-grid-fill"></i>
                                </div>
                                <div class="breadcomb-ctn">
                                    <h2>@yield('page-title', 'Dashboard')</h2>
                                    <p>PT Techno Drive Engineering &bull; <span class="font-mono-tech text-muted" style="font-size:11px;">SISTEM MANAJEMEN OPERASIONAL PROYEK</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12 text-md-end mt-2 mt-md-0">
                            @yield('breadcrumb-actions')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dynamic Content Slot -->
        @yield('content')

        <!-- Footer Area -->
        <div class="footer-copyright-area mt-auto">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-12 text-center">
                        <div class="footer-copy-right">
                            <p class="mb-0">&copy; {{ date('Y') }} <strong>PT Techno Drive Engineering</strong>. Seluruh hak cipta dilindungi undang-undang.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- End .all-content-wrapper -->

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
    @endauth

    @guest
        @yield('content')
    @endguest

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simplebar@6.2.7/dist/simplebar.min.js"></script>
    <script src="{{ asset('nalika/js/main.js') }}"></script>

    @stack('scripts')

</body>
</html>

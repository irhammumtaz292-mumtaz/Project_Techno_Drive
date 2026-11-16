<header role="banner" class="industrial-header">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid px-lg-4">
            <!-- Brand Logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <i class="fa fa-cogs text-warning me-2 fs-4"></i>
                <span>TECHNO <span class="text-orange">DRIVE</span></span>
            </a>

            <!-- Mobile Toggler -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#industrialNavbar" aria-controls="industrialNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="industrialNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fa fa-dashboard me-1"></i> Dashboard
                        </a>
                    </li>

                    @auth
                        <!-- Menu Khusus Supervisor -->
                        @if(Auth::user()->role === 'Supervisor')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('projects.index') ? 'active' : '' }}" href="{{ route('projects.index') }}">
                                    <i class="fa fa-folder-open me-1"></i> Kelola Proyek
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('projects.create') ? 'active' : '' }}" href="{{ route('projects.create') }}">
                                    <i class="fa fa-plus-circle me-1 text-warning"></i> Registrasi Proyek
                                </a>
                            </li>
                            <!-- Dropdown Master Data -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ request()->is('customers*') || request()->is('end_users*') || request()->is('equipments*') || request()->is('staffs*') ? 'active' : '' }}" href="#" id="masterDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-database me-1"></i> Master Data
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark shadow" aria-labelledby="masterDropdown">
                                    <li><a class="dropdown-item {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}"><i class="fa fa-building me-2"></i> Master Customer</a></li>
                                    <li><a class="dropdown-item {{ request()->routeIs('end_users.*') ? 'active' : '' }}" href="{{ route('end_users.index') }}"><i class="fa fa-map-marker me-2"></i> Master End User</a></li>
                                    <li><a class="dropdown-item {{ request()->routeIs('equipments.*') ? 'active' : '' }}" href="{{ route('equipments.index') }}"><i class="fa fa-wrench me-2"></i> Master Alat Uji</a></li>
                                    <li><hr class="dropdown-divider border-secondary"></li>
                                    <li><a class="dropdown-item {{ request()->routeIs('staffs.*') ? 'active' : '' }}" href="{{ route('staffs.index') }}"><i class="fa fa-users me-2"></i> Master Staff & Akun</a></li>
                                </ul>
                            </li>
                        @endif

                        <!-- Menu Khusus Director -->
                        @if(Auth::user()->role === 'Director')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('projects.index') ? 'active' : '' }}" href="{{ route('projects.index') }}">
                                    <i class="fa fa-briefcase me-1"></i> Semua Proyek
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-warning" href="{{ route('projects.index') }}">
                                    <i class="fa fa-exclamation-triangle me-1"></i> Pending Reports
                                </a>
                            </li>
                        @endif

                        <!-- Menu Khusus Staff -->
                        @if(Auth::user()->role === 'Staff')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('projects.index') ? 'active' : '' }}" href="{{ route('projects.index') }}">
                                    <i class="fa fa-tasks me-1"></i> Tugas & Proyek
                                </a>
                            </li>
                        @endif

                        <!-- Menu Khusus Finance & Logistics -->
                        @if(in_array(Auth::user()->role, ['Finance', 'Logistics']))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('projects.index') ? 'active' : '' }}" href="{{ route('projects.index') }}">
                                    <i class="fa fa-folder-open me-1"></i> Data Proyek
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-warning" href="{{ route('projects.index') }}">
                                    <i class="fa fa-check-square-o me-1"></i> Verifikasi SPK
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <!-- User Profile & Logout di Sisi Kanan -->
                @auth
                    <div class="d-flex align-items-center ms-auto pt-2 pt-lg-0">
                        <div class="me-3 text-end d-none d-sm-block">
                            <span class="d-block text-white fw-bold small text-uppercase">
                                <i class="fa fa-user-circle me-1 text-warning"></i> {{ Auth::user()->name }}
                            </span>
                            <span class="badge bg-warning text-dark text-uppercase px-2 py-1" style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                {{ Auth::user()->role }}
                            </span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-warning text-uppercase px-3 py-1 fw-bold" style="letter-spacing: 0.1em; font-size: 0.75rem;">
                                <i class="fa fa-sign-out me-1"></i> Logout
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>
    <div class="top-accent-line"></div>
</header>

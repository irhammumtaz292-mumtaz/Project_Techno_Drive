<header role="banner" class="industrial-header">
    <nav class="navbar navbar-expand-lg navbar-dark bg-[#111111] py-2" style="background-color: #111111 !important; border-bottom: 1px solid #222222;">
        <div class="container-fluid px-lg-4">
            <!-- Brand Logo (Architectural Precision) -->
            <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="{{ route('dashboard') }}">
                <span class="d-inline-flex align-items-center justify-content-center bg-white text-dark fw-bold font-mono-tech px-2 py-1" style="font-size: 0.85rem; letter-spacing: 0.05em;">
                    TD
                </span>
                <span class="fw-bold tracking-tight text-white text-uppercase" style="font-size: 1.05rem; letter-spacing: 0.02em;">
                    Techno Drive<span style="color: var(--accent-orange);">.</span>
                </span>
            </a>

            <!-- Mobile Toggler -->
            <button class="navbar-toggler border-0 rounded-0" type="button" data-bs-toggle="collapse" data-bs-target="#industrialNavbar" aria-controls="industrialNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="industrialNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link text-uppercase font-mono-tech px-3 {{ request()->routeIs('dashboard') ? 'active text-white border-bottom border-2' : 'text-white-50' }}" 
                           style="{{ request()->routeIs('dashboard') ? 'border-color: var(--accent-orange) !important;' : '' }} font-size: 0.78rem;"
                           href="{{ route('dashboard') }}">
                            01 // Dashboard
                        </a>
                    </li>

                    @auth
                        <!-- Menu Khusus Supervisor -->
                        @if(Auth::user()->role === 'Supervisor')
                            <li class="nav-item">
                                <a class="nav-link text-uppercase font-mono-tech px-3 {{ request()->routeIs('projects.*') ? 'active text-white border-bottom border-2' : 'text-white-50' }}" 
                                   style="{{ request()->routeIs('projects.*') ? 'border-color: var(--accent-orange) !important;' : '' }} font-size: 0.78rem;"
                                   href="{{ route('projects.index') }}">
                                    02 // Kelola Proyek
                                </a>
                            </li>

                            <!-- Dropdown Master Data -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-uppercase font-mono-tech px-3 {{ request()->is('customers*') || request()->is('end_users*') || request()->is('equipments*') || request()->is('staffs*') ? 'active text-white' : 'text-white-50' }}" 
                                   href="#" id="masterDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                                   style="font-size: 0.78rem;">
                                    03 // Master Data
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark rounded-0 shadow-none border border-secondary" aria-labelledby="masterDropdown" style="background-color: #1a1a1a; font-size: 0.8rem;">
                                    <li><a class="dropdown-item py-2 {{ request()->routeIs('customers.*') ? 'bg-black text-white' : '' }}" href="{{ route('customers.index') }}"><i class="fa fa-building me-2 text-white-50"></i> Master Customer</a></li>
                                    <li><a class="dropdown-item py-2 {{ request()->routeIs('end_users.*') ? 'bg-black text-white' : '' }}" href="{{ route('end_users.index') }}"><i class="fa fa-map-marker me-2 text-white-50"></i> Master End User</a></li>
                                    <li><a class="dropdown-item py-2 {{ request()->routeIs('equipments.*') ? 'bg-black text-white' : '' }}" href="{{ route('equipments.index') }}"><i class="fa fa-wrench me-2 text-white-50"></i> Master Alat Uji</a></li>
                                    <li><hr class="dropdown-divider border-secondary my-1"></li>
                                    <li><a class="dropdown-item py-2 {{ request()->routeIs('staffs.*') ? 'bg-black text-white' : '' }}" href="{{ route('staffs.index') }}"><i class="fa fa-users me-2 text-white-50"></i> Master Staff &amp; Akun</a></li>
                                </ul>
                            </li>
                        @endif

                        <!-- Menu Khusus Director -->
                        @if(Auth::user()->role === 'Director')
                            <li class="nav-item">
                                <a class="nav-link text-uppercase font-mono-tech px-3 {{ request()->routeIs('projects.index') ? 'active text-white border-bottom border-2' : 'text-white-50' }}" 
                                   style="{{ request()->routeIs('projects.index') ? 'border-color: var(--accent-orange) !important;' : '' }} font-size: 0.78rem;"
                                   href="{{ route('projects.index') }}">
                                    02 // Semua Proyek
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-uppercase font-mono-tech px-3 text-white-50" href="{{ route('projects.index') }}" style="font-size: 0.78rem;">
                                    03 // Pending Reports
                                </a>
                            </li>
                        @endif

                        <!-- Menu Khusus Staff -->
                        @if(Auth::user()->role === 'Staff')
                            <li class="nav-item">
                                <a class="nav-link text-uppercase font-mono-tech px-3 {{ request()->routeIs('projects.index') ? 'active text-white border-bottom border-2' : 'text-white-50' }}" 
                                   style="{{ request()->routeIs('projects.index') ? 'border-color: var(--accent-orange) !important;' : '' }} font-size: 0.78rem;"
                                   href="{{ route('projects.index') }}">
                                    02 // Tugas &amp; Proyek
                                </a>
                            </li>
                        @endif

                        <!-- Menu Khusus Finance & Logistics -->
                        @if(in_array(Auth::user()->role, ['Finance', 'Logistics']))
                            <li class="nav-item">
                                <a class="nav-link text-uppercase font-mono-tech px-3 {{ request()->routeIs('projects.index') ? 'active text-white border-bottom border-2' : 'text-white-50' }}" 
                                   style="{{ request()->routeIs('projects.index') ? 'border-color: var(--accent-orange) !important;' : '' }} font-size: 0.78rem;"
                                   href="{{ route('projects.index') }}">
                                    02 // Data Proyek
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-uppercase font-mono-tech px-3 text-white-50" href="{{ route('projects.index') }}" style="font-size: 0.78rem;">
                                    03 // Verifikasi SPK
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <!-- User Profile & Logout di Sisi Kanan -->
                @auth
                    <div class="d-flex align-items-center ms-auto pt-2 pt-lg-0 gap-3">
                        <div class="text-end d-none d-sm-block">
                            <span class="d-block text-white fw-bold small text-uppercase" style="letter-spacing: 0.03em;">
                                {{ Auth::user()->name }}
                            </span>
                            <span class="font-mono-tech text-uppercase text-white-50" style="font-size: 0.68rem;">
                                [ ROLE: {{ Auth::user()->role }} ]
                            </span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary text-white text-uppercase font-mono-tech px-3 py-1" style="font-size: 0.7rem; border-color: #444444 !important;">
                                Logout ↗
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </div>
    </nav>
    <div class="top-accent-line"></div>
</header>

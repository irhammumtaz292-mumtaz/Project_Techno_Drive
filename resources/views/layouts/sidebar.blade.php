<!-- Admin Sidebar Navigation (Swiss Architectural Grid / Industrial Precision) -->
<div class="offcanvas-lg offcanvas-start admin-sidebar d-flex flex-column flex-shrink-0" tabindex="-1" id="adminSidebar" style="width: 270px; background-color: var(--carbon); border-right: 1px solid #222222; min-height: 100vh;">
    
    <!-- Sidebar Header / Brand Identity -->
    <div class="d-flex align-items-center justify-content-between px-4 py-3 border-bottom" style="border-color: #222222 !important; height: 65px;">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <span class="d-inline-flex align-items-center justify-content-center bg-white text-dark fw-bold font-mono-tech px-2 py-0.5" style="font-size: 0.85rem;">
                TD
            </span>
            <span class="fw-bold tracking-tight text-white text-uppercase" style="font-size: 1.05rem; letter-spacing: 0.02em;">
                Techno Drive<span style="color: var(--accent-orange);">.</span>
            </span>
        </a>
        <button type="button" class="btn-close btn-close-white d-lg-none" data-bs-dismiss="offcanvas" data-bs-target="#adminSidebar" aria-label="Close"></button>
    </div>

    <!-- Active User Dossier -->
    @auth
    <div class="px-4 py-3 border-bottom" style="border-color: #222222 !important; background-color: #161616;">
        <div class="font-mono-tech text-uppercase text-white-50" style="font-size: 0.65rem; letter-spacing: 0.08em;">
            [ OTORISASI OPERASIONAL ]
        </div>
        <div class="fw-bold text-white small text-uppercase text-truncate mt-1">
            {{ Auth::user()->name }}
        </div>
        <div class="d-flex align-items-center gap-2 mt-1">
            <span class="badge rounded-0 font-mono-tech px-2 py-0.5" style="font-size: 0.65rem; background-color: #262626; color: var(--accent-orange); border: 1px solid #333333;">
                {{ Auth::user()->role }}
            </span>
            <span class="font-mono-tech text-white-50" style="font-size: 0.65rem;">
                • AKTIF
            </span>
        </div>
    </div>
    @endauth

    <!-- Navigation Menu Items -->
    <div class="flex-grow-1 overflow-y-auto py-3">
        
        <!-- SECTION 01: MODUL UTAMA -->
        <div class="px-4 pb-1 pt-2 font-mono-tech text-uppercase text-white-50" style="font-size: 0.65rem; letter-spacing: 0.08em;">
            [ 01 // MODUL UTAMA ]
        </div>
        <ul class="nav nav-pills flex-column mb-3">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" 
                   class="nav-link rounded-0 font-mono-tech text-uppercase py-2.5 px-4 d-flex align-items-center gap-2 {{ request()->routeIs('dashboard') ? 'active-nav-item' : 'text-white-50 hover-nav-item' }}"
                   style="font-size: 0.78rem;">
                    <i class="bi bi-speedometer2 text-white-50"></i>
                    <span>Dashboard</span>
                </a>
            </li>
        </ul>

        <!-- SECTION 02: MANAJEMEN PROYEK -->
        <div class="px-4 pb-1 pt-2 font-mono-tech text-uppercase text-white-50" style="font-size: 0.65rem; letter-spacing: 0.08em;">
            [ 02 // PROYEK &amp; LAPORAN ]
        </div>
        <ul class="nav nav-pills flex-column mb-3">
            
            @if(Auth::user()->role === 'Supervisor')
                <li class="nav-item">
                    <a href="{{ route('projects.index') }}" 
                       class="nav-link rounded-0 font-mono-tech text-uppercase py-2.5 px-4 d-flex align-items-center gap-2 {{ request()->routeIs('projects.*') ? 'active-nav-item' : 'text-white-50 hover-nav-item' }}"
                       style="font-size: 0.78rem;">
                        <i class="bi bi-folder-check text-white-50"></i>
                        <span>Kelola Proyek</span>
                    </a>
                </li>
            @endif

            @if(Auth::user()->role === 'Director')
                <li class="nav-item">
                    <a href="{{ route('projects.index') }}" 
                       class="nav-link rounded-0 font-mono-tech text-uppercase py-2.5 px-4 d-flex align-items-center gap-2 {{ request()->routeIs('projects.index') ? 'active-nav-item' : 'text-white-50 hover-nav-item' }}"
                       style="font-size: 0.78rem;">
                        <i class="bi bi-folder2-open text-white-50"></i>
                        <span>Semua Proyek</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('projects.index') }}" 
                       class="nav-link rounded-0 font-mono-tech text-uppercase py-2.5 px-4 d-flex align-items-center gap-2 text-white-50 hover-nav-item"
                       style="font-size: 0.78rem;">
                        <i class="bi bi-exclamation-triangle text-danger"></i>
                        <span class="text-danger">Pending Reports</span>
                    </a>
                </li>
            @endif

            @if(Auth::user()->role === 'Staff')
                <li class="nav-item">
                    <a href="{{ route('projects.index') }}" 
                       class="nav-link rounded-0 font-mono-tech text-uppercase py-2.5 px-4 d-flex align-items-center gap-2 {{ request()->routeIs('projects.index') ? 'active-nav-item' : 'text-white-50 hover-nav-item' }}"
                       style="font-size: 0.78rem;">
                        <i class="bi bi-clipboard-check text-white-50"></i>
                        <span>Tugas Saya</span>
                    </a>
                </li>
            @endif

            @if(in_array(Auth::user()->role, ['Finance', 'Logistics']))
                <li class="nav-item">
                    <a href="{{ route('projects.index') }}" 
                       class="nav-link rounded-0 font-mono-tech text-uppercase py-2.5 px-4 d-flex align-items-center gap-2 {{ request()->routeIs('projects.index') ? 'active-nav-item' : 'text-white-50 hover-nav-item' }}"
                       style="font-size: 0.78rem;">
                        <i class="bi bi-folder-open text-white-50"></i>
                        <span>Data Proyek</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('projects.index') }}" 
                       class="nav-link rounded-0 font-mono-tech text-uppercase py-2.5 px-4 d-flex align-items-center gap-2 text-white-50 hover-nav-item"
                       style="font-size: 0.78rem;">
                        <i class="bi bi-check-square text-white-50"></i>
                        <span>Verifikasi SPK</span>
                    </a>
                </li>
            @endif

        </ul>

        <!-- SECTION 03: MASTER DATA (SUPERVISOR) -->
        @if(Auth::user()->role === 'Supervisor')
        <div class="px-4 pb-1 pt-2 font-mono-tech text-uppercase text-white-50" style="font-size: 0.65rem; letter-spacing: 0.08em;">
            [ 03 // MASTER DATA ]
        </div>
        <ul class="nav nav-pills flex-column mb-3">
            <li class="nav-item">
                <a href="{{ route('customers.index') }}" 
                   class="nav-link rounded-0 font-mono-tech text-uppercase py-2 px-4 d-flex align-items-center gap-2 {{ request()->routeIs('customers.*') ? 'active-nav-item' : 'text-white-50 hover-nav-item' }}"
                   style="font-size: 0.76rem;">
                    <i class="bi bi-building text-white-50"></i>
                    <span>Master Customer</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('end_users.index') }}" 
                   class="nav-link rounded-0 font-mono-tech text-uppercase py-2 px-4 d-flex align-items-center gap-2 {{ request()->routeIs('end_users.*') ? 'active-nav-item' : 'text-white-50 hover-nav-item' }}"
                   style="font-size: 0.76rem;">
                    <i class="bi bi-geo-alt text-white-50"></i>
                    <span>Master End User</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('equipments.index') }}" 
                   class="nav-link rounded-0 font-mono-tech text-uppercase py-2 px-4 d-flex align-items-center gap-2 {{ request()->routeIs('equipments.*') ? 'active-nav-item' : 'text-white-50 hover-nav-item' }}"
                   style="font-size: 0.76rem;">
                    <i class="bi bi-tools text-white-50"></i>
                    <span>Master Alat Uji</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('staffs.index') }}" 
                   class="nav-link rounded-0 font-mono-tech text-uppercase py-2 px-4 d-flex align-items-center gap-2 {{ request()->routeIs('staffs.*') ? 'active-nav-item' : 'text-white-50 hover-nav-item' }}"
                   style="font-size: 0.76rem;">
                    <i class="bi bi-people text-white-50"></i>
                    <span>Master Staff &amp; Akun</span>
                </a>
            </li>
        </ul>
        @endif

    </div>

    <!-- Sidebar Footer / Logout -->
    <div class="p-3 border-top" style="border-color: #222222 !important; background-color: #0c0c0c;">
        <div class="d-flex justify-content-between align-items-center">
            <span class="font-mono-tech text-white-50" style="font-size: 0.65rem;">
                SYSTEM V2.6 // K3
            </span>
            <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-secondary font-mono-tech text-uppercase px-2 py-1 text-white" style="font-size: 0.68rem; border-color: #333333 !important;">
                    Logout ↗
                </button>
            </form>
        </div>
    </div>

</div>

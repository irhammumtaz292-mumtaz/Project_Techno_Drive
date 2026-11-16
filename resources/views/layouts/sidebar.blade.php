<ul class="sidebar-menu">
    <li>
        <a href="{{ route('dashboard') }}" class="active">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
    </li>

    <!-- Menu Khusus Supervisor -->
        @if(Auth::user()->role == 'Supervisor')
    <li>
        <a href="{{ route('customers.index') }}"><i class="bi bi-building"></i> Master Customer</a>
    </li>
    <li>
        <a href="{{ route('end_users.index') }}"><i class="bi bi-geo-alt"></i> Master End User</a>
    </li>
    <li>
        <a href="{{ route('equipments.index') }}"><i class="bi bi-tools"></i> Master Alat Uji</a>
    </li>
    <li>
        <a href="{{ route('staffs.index') }}"><i class="bi bi-people"></i> Master Staff & Akun</a>
    </li>
    <li>
    <a href="{{ route('projects.index') }}"><i class="bi bi-folder-check"></i> Kelola Proyek</a>
    </li>
    @endif

    <!-- Menu Khusus Director -->
    @if(Auth::user()->role == 'Director')
    <li>
    <a href="{{ route('projects.index') }}"><i class="bi bi-folder2-open"></i> Semua Proyek</a>
</li>
    <li>
        <a href="#"><i class="bi bi-exclamation-triangle"></i> Pending Reports</a>
    </li>
    @endif

    <!-- Menu Khusus Staff -->
    @if(Auth::user()->role == 'Staff')
    <li>
        <a href="#"><i class="bi bi-clipboard-check"></i> Tugas Saya</a>
    </li>
    @endif

    <!-- Menu Khusus Finance / Logistics -->
    @if(in_array(Auth::user()->role, ['Finance', 'Logistics']))
    <li>
        <a href="#"><i class="bi bi-check-circle"></i> Verifikasi SPK</a>
    </li>
    @endif
</ul>
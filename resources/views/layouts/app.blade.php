<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PT Techno Drive - Sistem Manajemen Proyek & Laporan</title>

    <!-- Google Fonts (Industrial Theme) -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700|Oxygen:400,700" rel="stylesheet">

    <!-- Industrial Icons & Animation -->
    <link rel="stylesheet" href="{{ asset('industrial/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('industrial/fonts/fontawesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('industrial/fonts/ionicons/css/ionicons.min.css') }}">

    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Industrial Custom Styling -->
    <style>
        :root {
            --ind-primary: #fd5f00;
            --ind-accent: #f4b214;
            --ind-dark: #121212;
            --ind-darker: #0a0a0a;
            --ind-bg: #f4f6f9;
        }

        body {
            font-family: 'Oxygen', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--ind-bg);
            color: #2b2f33;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            color: #1a1e21;
        }

        /* Industrial Top Navbar */
        .industrial-header .navbar {
            background-color: var(--ind-darker) !important;
            border-bottom: 1px solid #222;
            padding-top: 0.2rem;
            padding-bottom: 0.2rem;
        }

        .industrial-header .navbar-brand {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #fff;
        }

        .text-orange {
            color: var(--ind-primary) !important;
        }

        .industrial-header .nav-link {
            font-family: 'Montserrat', sans-serif;
            text-transform: uppercase;
            font-size: 0.78rem;
            letter-spacing: 0.12em;
            font-weight: 600;
            color: #adb5bd !important;
            padding: 1.1rem 0.85rem !important;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
        }

        .industrial-header .nav-link:hover,
        .industrial-header .nav-link.active {
            color: #ffffff !important;
            border-bottom-color: var(--ind-primary);
        }

        .industrial-header .dropdown-menu {
            border-radius: 4px;
            border: 1px solid #333;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
            margin-top: 0;
        }

        .industrial-header .dropdown-item {
            font-family: 'Montserrat', sans-serif;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.6rem 1.2rem;
            transition: 0.2s;
        }

        .industrial-header .dropdown-item:hover,
        .industrial-header .dropdown-item.active {
            background-color: var(--ind-primary);
            color: #fff;
        }

        .top-accent-line {
            height: 3px;
            background: linear-gradient(90deg, var(--ind-primary) 0%, var(--ind-accent) 100%);
            width: 100%;
        }

        /* Buttons Industrial Theme */
        .btn-primary {
            background-color: var(--ind-primary);
            border-color: var(--ind-primary);
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            transition: all 0.2s ease;
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: #e05300;
            border-color: #e05300;
            box-shadow: 0 4px 12px rgba(253, 95, 0, 0.3);
        }

        .btn-outline-warning {
            color: var(--ind-accent);
            border-color: var(--ind-accent);
        }

        .btn-outline-warning:hover {
            background-color: var(--ind-accent);
            color: #111;
        }

        /* Cards & Content */
        .card {
            border: none;
            border-radius: 6px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #edf2f7;
            font-family: 'Montserrat', sans-serif;
        }

        /* Select2 Theme Tweaks */
        .select2-container--classic .select2-selection--single,
        .select2-container--classic .select2-selection--multiple {
            border-color: #dee2e6;
            min-height: 38px;
            border-radius: 4px;
        }

        .select2-container--classic.select2-container--focus .select2-selection--multiple,
        .select2-container--classic.select2-container--focus .select2-selection--single {
            border-color: var(--ind-primary);
        }

        /* Footer */
        footer.industrial-footer {
            margin-top: auto;
            background-color: var(--ind-darker);
            color: #888;
            font-size: 0.82rem;
            padding: 1.2rem 0;
            border-top: 1px solid #222;
        }
    </style>
</head>
<body>

    @auth
        <!-- Navbar di Posisi Atas (Sesuai Template Industrial) -->
        @include('layouts.navbar')

        <!-- Main Content Area -->
        <main class="py-4 flex-grow-1">
            <div class="container-fluid px-lg-4">
                @yield('content')
            </div>
        </main>
    @else
        <!-- Halaman Tamu / Login -->
        <main class="flex-grow-1">
            @yield('content')
        </main>
    @endauth

    <!-- Industrial Footer -->
    <footer class="industrial-footer text-center">
        <div class="container-fluid px-lg-4 d-flex flex-wrap justify-content-between align-items-center">
            <span>&copy; {{ date('Y') }} <strong>PT Techno Drive</strong>. All rights reserved.</span>
            <span class="text-orange fw-bold font-monospace" style="font-size: 0.75rem;">INTERNAL PROJECT & REPORT SYSTEM</span>
        </div>
    </footer>

    <!-- jQuery 3.7.1 (Dependensi Utama Select2 & Fitur Interaktif) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @yield('scripts')
</body>
</html>

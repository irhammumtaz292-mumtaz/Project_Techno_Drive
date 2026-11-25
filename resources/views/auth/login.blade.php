<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Login | PT Techno Drive Engineering</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('img/logo_TD.png') }}?v={{ time() }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/logo_TD.png') }}?v={{ time() }}">   
     <!-- Google Fonts: Plus Jakarta Sans (Heading/Logo) + Inter (Body/UI) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body {
            background: #111111;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .nk-auth-card {
            background: #181818;
            border: 1px solid #282828;
            border-radius: 4px;
            width: 100%;
            max-width: 420px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        }
        /* Logo pakai Plus Jakarta Sans (Heading Font) */
        .nk-auth-logo {
            font-size: 26px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: 2px;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .nk-auth-logo span { color: #FF5500; }
        /* Subtitle / meta pakai Inter */
        .nk-auth-subtitle {
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            color: #888888;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 500;
        }
        .nk-auth-card .form-control {
            background: #111111;
            border: 1px solid #333333;
            color: #FFFFFF;
            border-radius: 3px;
            padding: 10px 14px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
        }
        .nk-auth-card .form-control:focus {
            border-color: #FF5500;
            box-shadow: 0 0 0 2px rgba(255,85,0,0.2);
            background: #111111;
            color: #FFFFFF;
        }
        .nk-auth-card .form-control::placeholder { color: #555555; }
        .nk-auth-card .form-label {
            color: #CCCCCC;
            font-size: 13px;
            font-weight: 500;
            font-family: 'Inter', sans-serif;
        }
        .btn-nk-primary {
            background: #FF5500;
            border: none;
            color: #FFFFFF;
            border-radius: 3px;
            padding: 11px;
            font-weight: 700;
            font-size: 14px;
            transition: all 0.2s;
            font-family: 'Inter', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .btn-nk-primary:hover { background: #FFFFFF; color: #111111; }
        .nk-auth-footer {
            color: #777777;
            font-size: 12px;
            font-family: 'Inter', sans-serif;
        }
        .alert-danger-nk {
            background: rgba(239,68,68,0.15);
            border: 1px solid rgba(239,68,68,0.4);
            border-radius: 3px;
            color: #FCA5A5;
            font-size: 13px;
            padding: 10px 14px;
            font-family: 'Inter', sans-serif;
        }
        .td-company-label {
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #555555;
            margin-top: 24px;
        }
    </style>
</head>
<body>
    <div class="text-center px-3 w-100">
        <div class="nk-auth-card mx-auto mb-4">
            <!-- Brand Logo -->
            <div class="nk-auth-logo mb-1">TECHNO<span>DRIVE</span></div>
            <p class="nk-auth-subtitle mb-1">PT Techno Drive Engineering</p>
            <p class="mb-4" style="font-size:12.5px; color:#777777;">
                Sistem Informasi Manajemen Operasional Proyek
            </p>

            @if($errors->any())
            <div class="alert-danger-nk mb-3 text-start">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                {{ $errors->first() }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert-danger-nk mb-3 text-start">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                {{ session('error') }}
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                @csrf
                <div class="mb-3 text-start">
                    <label class="form-label" for="email">Username / Email</label>
                    <input type="text" id="email" name="email" class="form-control"
                        placeholder="Masukkan username atau email" required
                        value="{{ old('email', old('username')) }}" autocomplete="username">
                </div>
                <div class="mb-4 text-start">
                    <label class="form-label d-flex justify-content-between" for="password">
                        Password
                    </label>
                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Masukkan password" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn btn-nk-primary w-100 mb-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Sistem
                </button>
            </form>
        </div>

        <div class="nk-auth-footer mt-2">
            <p>&copy; {{ date('Y') }} PT Techno Drive Engineering. Seluruh hak cipta dilindungi.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

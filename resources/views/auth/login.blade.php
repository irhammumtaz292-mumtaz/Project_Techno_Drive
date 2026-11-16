@extends('layouts.app')

@section('content')
<div class="position-relative d-flex align-items-center justify-content-center" style="min-height: 90vh; background: linear-gradient(rgba(10, 10, 10, 0.75), rgba(10, 10, 10, 0.85)), url('{{ asset('industrial/images/industrial_hero_1.jpg') }}') center/cover no-repeat;">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-lg border-0 overflow-hidden" style="border-radius: 8px;">
                    <!-- Card Header Industrial -->
                    <div class="card-header bg-dark text-white text-center py-4 border-0 position-relative">
                        <div class="mb-2">
                            <i class="fa fa-cogs text-warning" style="font-size: 2.5rem;"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-white text-uppercase tracking-wider">TECHNO <span class="text-orange">DRIVE</span></h4>
                        <small class="text-white-50 letter-spacing-1">Sistem Manajemen Proyek Internal</small>
                        <div class="position-absolute bottom-0 start-0 w-100 top-accent-line"></div>
                    </div>

                    <!-- Card Body Form -->
                    <div class="card-body p-4 p-md-5 bg-white">
                        <h6 class="text-uppercase fw-bold text-center mb-4 text-muted" style="letter-spacing: 0.1em; font-size: 0.85rem;">
                            Login Pegawai
                        </h6>

                        @if ($errors->any())
                            <div class="alert alert-danger py-2 small" role="alert">
                                <i class="fa fa-exclamation-circle me-1"></i> {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-uppercase text-muted">Alamat Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa fa-envelope-o"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0" placeholder="nama@technodrive.com" value="{{ old('email') }}" required autofocus>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase text-muted">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0" placeholder=" " required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold text-uppercase shadow-sm">
                                <i class="fa fa-sign-in me-1"></i> Masuk ke Sistem
                            </button>
                        </form>
                    </div>

                    <!-- Card Footer Note -->
                    <div class="card-footer bg-light text-center py-3 border-0 small text-muted">
                        Akses khusus staf & manajemen PT Techno Drive
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-body text-center p-5">
            <h1 class="mb-3">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <h4 class="text-muted mb-4">Jabatan Anda: <strong>{{ Auth::user()->role }}</strong></h4>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>
</div>
@endsection
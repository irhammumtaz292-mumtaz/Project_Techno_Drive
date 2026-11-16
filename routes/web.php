<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EndUserController;
use App\Http\Controllers\TestEquipmentController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ProjectController;

// Halaman Login Publik
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// === AREA UTAMA (HARUS LOGIN) ===
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- MASTER DATA & PROYEK (Hanya Supervisor yang boleh mengelola/tambah) ---
    Route::middleware(['role:Supervisor'])->group(function () {
        Route::resource('customers', CustomerController::class);
        Route::resource('end_users', EndUserController::class);
        Route::resource('equipments', TestEquipmentController::class);
        Route::resource('staffs', StaffController::class);

        // Form & Proses Registrasi Proyek Baru + Update Pembayaran
        Route::get('/projects-create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
        Route::patch('/projects/{project}/update-payment', [ProjectController::class, 'updatePaymentStatus'])->name('projects.update-payment');
    });

    // --- AKSES BERSAMA: Lihat daftar & detail proyek (Semua role yang sudah login) ---
    // Supervisor & Director: lihat semua proyek
    // Staff, Finance, Logistics: lihat proyek yang relevan dengan peran mereka
    Route::middleware(['role:Supervisor,Director,Staff,Finance,Logistics'])->group(function () {
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/projects/{project}/download-po', [ProjectController::class, 'downloadPoPdf'])->name('projects.download-po');
    });

});
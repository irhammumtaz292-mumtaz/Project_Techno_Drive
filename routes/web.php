<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EndUserController;
use App\Http\Controllers\TestEquipmentController;
use App\Http\Controllers\MasterController;
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
    Route::middleware(['role:Supervisor,supervisor'])->group(function () {
        Route::resource('customers', CustomerController::class);
        Route::resource('end_users', EndUserController::class);
        Route::resource('staffs', StaffController::class);

        // Form & Proses Registrasi Proyek Baru
        Route::get('/projects-create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    });

    // --- VERIFIKASI PEMBAYARAN (Hanya Finance / Keuangan) ---
    Route::middleware(['role:Finance,finance,keuangan'])->group(function () {
        Route::get('/payment-verification', [ProjectController::class, 'paymentIndex'])->name('payment.index');
        Route::patch('/projects/{project}/update-payment', [ProjectController::class, 'updatePaymentStatus'])->name('projects.update-payment');
    });

    // --- INVENTORI ALAT: READ — Supervisor, Director/Direktur, Logistics/Logistik ---
    Route::middleware(['role:Supervisor,supervisor,Director,director,direktur,Logistics,logistics,logistik'])->group(function () {
        Route::get('/equipments', [TestEquipmentController::class, 'index'])->name('equipments.index');
        Route::get('/equipments/{equipment}', [TestEquipmentController::class, 'show'])->name('equipments.show');
        Route::get('/tools', [MasterController::class, 'indexTool'])->name('tools.index');
    });

    // --- INVENTORI ALAT: WRITE — Khusus Role Logistik / Logistics ---
    Route::middleware(['role:Logistics,logistics,logistik'])->group(function () {
        Route::get('/equipments/create', [TestEquipmentController::class, 'create'])->name('equipments.create');
        Route::post('/equipments', [TestEquipmentController::class, 'store'])->name('equipments.store');
        Route::get('/equipments/{equipment}/edit', [TestEquipmentController::class, 'edit'])->name('equipments.edit');
        Route::put('/equipments/{equipment}', [TestEquipmentController::class, 'update'])->name('equipments.update');
        Route::delete('/equipments/{equipment}', [TestEquipmentController::class, 'destroy'])->name('equipments.destroy');
        // Toggle status ketersediaan alat
        Route::patch('/equipments/{equipment}/toggle-status', [TestEquipmentController::class, 'toggleStatus'])->name('equipments.toggle-status');

        // MasterController Tool endpoints
        Route::get('/tools/create', [MasterController::class, 'createTool'])->name('tools.create');
        Route::post('/tools', [MasterController::class, 'storeTool'])->name('tools.store');
        Route::patch('/tools/{id}/toggle-status', [MasterController::class, 'toggleStatus'])->name('tools.toggle-status');
    });

    // --- AKSES BERSAMA: Lihat daftar & detail proyek (Semua role yang sudah login) ---
    // Supervisor & Director: lihat semua proyek
    // Staff, Finance, Logistics: lihat proyek yang relevan dengan peran mereka
    Route::middleware(['role:Supervisor,supervisor,Director,director,direktur,Staff,staff,Finance,finance,keuangan,Logistics,logistics,logistik'])->group(function () {
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/projects/{project}/download-po', [ProjectController::class, 'downloadPoPdf'])->name('projects.download-po');
    });

});

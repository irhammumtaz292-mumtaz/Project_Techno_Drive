<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Membuat tabel utama projects
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Customer dan End User
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('end_user_id')->constrained('end_users')->onDelete('cascade');
            
            $table->string('name');
            $table->text('description')->nullable();
            
            // Jadwal
            $table->date('tgl_mulai')->nullable();
            $table->date('tgl_selesai')->nullable();
            $table->date('tgl_jatuh_tempo_kontrak');
            $table->date('deadline_laporan');
            
            // Status 
            $table->enum('status_pekerjaan', ['On Progress', 'Completed'])->default('On Progress');
            $table->enum('status_pembayaran', ['Waiting Payment', 'Paid'])->default('Waiting Payment');
            
            $table->timestamps();
        });

        // 2. Membuat tabel pivot relasi project_user (staf yang ditugaskan)
        Schema::create('project_user', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->primary(['project_id', 'user_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_user');
        Schema::dropIfExists('projects');
    }
};
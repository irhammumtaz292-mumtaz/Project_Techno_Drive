<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
        $table->string('no_spk')->unique(); // Format otomatis: 012/TD/SPK/XI/2026
        $table->date('tgl_spk');
        
        // Status alur verifikasi bertahap
        $table->enum('status_verifikasi', [
            'Draft', 
            'Verifikasi Logistik', 
            'Verifikasi Keuangan', 
            'Persetujuan Direktur', 
            'Terbit', 
            'Dibatalkan'
        ])->default('Draft');
        
        // Pencatatan jejak audit (siapa dan kapan)
        $table->unsignedBigInteger('logistics_by')->nullable();
        $table->timestamp('logistics_at')->nullable();
        
        $table->unsignedBigInteger('finance_by')->nullable();
        $table->timestamp('finance_at')->nullable();
        
        $table->unsignedBigInteger('director_by')->nullable();
        $table->timestamp('director_at')->nullable();
        
        $table->text('alasan_batal')->nullable(); // Jika dibatalkan
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('spks');
    }
};
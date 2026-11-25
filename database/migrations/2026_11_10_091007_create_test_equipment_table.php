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
        Schema::create('test_equipments', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('serial_number')->unique();
        // Status diperluas sesuai masukan mitra
        $table->enum('status', ['Tersedia', 'Sedang Dipinjam', 'Sedang dipakai', 'Perbaikan', 'Kalibrasi'])->default('Tersedia');
        $table->date('calibration_expiry')->nullable(); // Masa berlaku kalibrasi
        $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_equipment');
    }
};

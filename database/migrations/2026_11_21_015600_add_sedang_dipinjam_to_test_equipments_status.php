<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `test_equipments` MODIFY COLUMN `status` ENUM('Tersedia', 'Sedang Dipinjam', 'Sedang dipakai', 'Perbaikan', 'Kalibrasi') NOT NULL DEFAULT 'Tersedia'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `test_equipments` MODIFY COLUMN `status` ENUM('Tersedia', 'Sedang dipakai', 'Perbaikan', 'Kalibrasi') NOT NULL DEFAULT 'Tersedia'");
    }
};

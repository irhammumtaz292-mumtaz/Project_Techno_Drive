<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menambahkan kolom:
     * - pic_id: Penanggung Jawab / PIC dari sisi PT Techno Drive (FK ke users)
     * - progress_notes: Catatan Progress Pekerjaan (terpisah dari description)
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // PIC dari sisi Techno Drive (bisa berbeda dari user yang menginput)
            $table->unsignedBigInteger('pic_id')->nullable()->after('end_user_id');
            $table->foreign('pic_id')->references('id')->on('users')->onDelete('set null');

            // Catatan progress pekerjaan (bukan deskripsi awal, tapi update progres)
            $table->text('progress_notes')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['pic_id']);
            $table->dropColumn(['pic_id', 'progress_notes']);
        });
    }
};

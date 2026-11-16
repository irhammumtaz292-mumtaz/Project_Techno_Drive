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
        Schema::create('purchase_orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
        $table->string('no_po')->unique(); // Nomor PO otomatis
        $table->date('tgl_po');
        $table->decimal('nilai_pekerjaan', 15, 2);
        $table->string('file_path')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};

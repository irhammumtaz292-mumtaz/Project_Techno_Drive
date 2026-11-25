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
        // 1. Buat tabel testing_tools jika belum ada
        if (!Schema::hasTable('testing_tools')) {
            Schema::create('testing_tools', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('brand_model');
                $table->integer('unit_count')->default(1);
                $table->string('status')->default('tersedia');
                $table->timestamps();
            });
        } else {
            Schema::table('testing_tools', function (Blueprint $table) {
                if (!Schema::hasColumn('testing_tools', 'name')) {
                    $table->string('name');
                }
                if (!Schema::hasColumn('testing_tools', 'brand_model')) {
                    $table->string('brand_model');
                }
                if (!Schema::hasColumn('testing_tools', 'unit_count')) {
                    $table->integer('unit_count')->default(1);
                }
                if (!Schema::hasColumn('testing_tools', 'status')) {
                    $table->string('status')->default('tersedia');
                }
            });
        }

        // 2. Sesuaikan tabel test_equipments agar memiliki kolom brand_model & unit_count
        if (Schema::hasTable('test_equipments')) {
            Schema::table('test_equipments', function (Blueprint $table) {
                if (!Schema::hasColumn('test_equipments', 'brand_model')) {
                    $table->string('brand_model')->nullable()->after('name');
                }
                if (!Schema::hasColumn('test_equipments', 'unit_count')) {
                    $table->integer('unit_count')->default(1)->after('brand_model');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testing_tools');
    }
};

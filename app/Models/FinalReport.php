<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalReport extends Model
{
    // Izinkan semua kolom kecuali primary key untuk mass assignment
    protected $guarded = ['id'];

    // Cast kolom tanggal otomatis ke tipe Carbon
    protected $casts = [
        'upload_date' => 'date',
    ];

    // =====================
    // RELASI ELOQUENT
    // =====================

    // Final Report dimiliki oleh satu Proyek
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // User (Staff) yang mengunggah laporan ini
    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // =====================
    // ACCESSOR / HELPER
    // =====================

    // Mendapatkan URL publik file laporan (membutuhkan php artisan storage:link)
    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    // Mendapatkan nama file saja dari path lengkap
    public function getFileNameAttribute(): string
    {
        return basename($this->file_path);
    }
}

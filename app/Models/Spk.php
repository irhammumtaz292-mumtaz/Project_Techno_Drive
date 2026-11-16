<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spk extends Model
{
    // Izinkan semua kolom kecuali primary key untuk mass assignment
    protected $guarded = ['id'];

    // Cast kolom tanggal/waktu otomatis ke tipe Carbon
    protected $casts = [
        'tgl_spk'       => 'date',
        'logistics_at'  => 'datetime',
        'finance_at'    => 'datetime',
        'director_at'   => 'datetime',
    ];

    // =====================
    // RELASI ELOQUENT
    // =====================

    // SPK dimiliki oleh satu Proyek
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // User yang melakukan verifikasi Logistik
    public function logisticsVerifier()
    {
        return $this->belongsTo(User::class, 'logistics_by');
    }

    // User yang melakukan verifikasi Keuangan
    public function financeVerifier()
    {
        return $this->belongsTo(User::class, 'finance_by');
    }

    // User yang memberikan Persetujuan Direktur
    public function directorApprover()
    {
        return $this->belongsTo(User::class, 'director_by');
    }

    // =====================
    // HELPER / SCOPE
    // =====================

    // Cek apakah SPK bisa diverifikasi oleh role tertentu
    public function canBeVerifiedBy(string $role): bool
    {
        return match ($role) {
            'Logistics' => $this->status_verifikasi === 'Draft',
            'Finance'   => $this->status_verifikasi === 'Verifikasi Logistik',
            'Director'  => $this->status_verifikasi === 'Verifikasi Keuangan',
            default     => false,
        };
    }
}

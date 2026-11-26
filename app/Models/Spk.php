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
        return match (self::normalizeVerifierRole($role)) {
            'Logistics' => $this->status_verifikasi === 'Draft',
            'Finance'   => $this->status_verifikasi === 'Verifikasi Logistik',
            'Director'  => in_array($this->status_verifikasi, ['Verifikasi Keuangan', 'Persetujuan Direktur'], true),
            default     => false,
        };
    }

    /**
     * Normalisasi nama role verifikator agar mendukung alias Indonesia/Inggris & case-insensitive.
     */
    public static function normalizeVerifierRole(?string $role): string
    {
        return match (strtolower(trim((string) $role))) {
            'logistics', 'logistik' => 'Logistics',
            'finance', 'keuangan'   => 'Finance',
            'director', 'direktur'  => 'Director',
            default                 => '',
        };
    }

    /**
     * Status tujuan berikutnya saat role verifikator menyetujui (Approve) SPK.
     */
    public function nextStatusForRole(string $role): ?string
    {
        if (! $this->canBeVerifiedBy($role)) {
            return null;
        }

        return match (self::normalizeVerifierRole($role)) {
            'Logistics' => 'Verifikasi Logistik',
            'Finance'   => 'Verifikasi Keuangan',
            'Director'  => 'Terbit',
            default     => null,
        };
    }

    /**
     * Prefix kolom jejak audit (*_by, *_at) sesuai role verifikator.
     */
    public static function auditPrefixForRole(string $role): ?string
    {
        return match (self::normalizeVerifierRole($role)) {
            'Logistics' => 'logistics',
            'Finance'   => 'finance',
            'Director'  => 'director',
            default     => null,
        };
    }
}

<?php

namespace App\Policies;

use App\Models\Spk;
use App\Models\User;

class SpkPolicy
{
    /**
     * Daftar SPK hanya dapat diakses oleh Supervisor, Logistics, Finance, dan Director.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Supervisor', 'Logistics', 'Finance', 'Director');
    }

    /**
     * Detail SPK dapat dilihat oleh aktor manajemen/verifikator,
     * atau Staff yang memang ditugaskan pada proyek terkait.
     */
    public function view(User $user, Spk $spk): bool
    {
        if ($user->hasRole('Supervisor', 'Logistics', 'Finance', 'Director')) {
            return true;
        }

        if ($user->hasRole('Staff')) {
            return $spk->project()->whereHas('staff', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })->exists();
        }

        return false;
    }

    /**
     * Hanya Supervisor yang dapat mengajukan Draft SPK baru.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Supervisor');
    }

    /**
     * Aksi Approve hanya diizinkan bagi role yang sedang mendapat giliran verifikasi tahap aktif.
     */
    public function approve(User $user, Spk $spk): bool
    {
        return $spk->canBeVerifiedBy((string) $user->role);
    }

    /**
     * Aksi Reject/Batalkan hanya diizinkan bagi role yang sedang mendapat giliran verifikasi tahap aktif.
     */
    public function reject(User $user, Spk $spk): bool
    {
        return $spk->canBeVerifiedBy((string) $user->role);
    }
}

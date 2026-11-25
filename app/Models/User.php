<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi: Staff mengerjakan banyak proyek
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user');
    }

    // Relasi: Staff mengunggah banyak final report
    public function finalReports()
    {
        return $this->hasMany(FinalReport::class);
    }

    /**
     * Memeriksa apakah user memiliki salah satu dari role yang ditentukan (case-insensitive & support sinonim)
     */
    public function hasRole(...$roles): bool
    {
        $myRole = strtolower(trim($this->role ?? ''));
        $myAliases = [$myRole];
        if (in_array($myRole, ['director', 'direktur'])) {
            $myAliases = ['director', 'direktur'];
        } elseif (in_array($myRole, ['logistics', 'logistik'])) {
            $myAliases = ['logistics', 'logistik'];
        } elseif (in_array($myRole, ['finance', 'keuangan'])) {
            $myAliases = ['finance', 'keuangan'];
        }

        $targets = [];
        foreach ($roles as $r) {
            foreach (explode(',', $r) as $sub) {
                $subNorm = strtolower(trim($sub));
                $targets[] = $subNorm;
                if ($subNorm === 'director' || $subNorm === 'direktur') {
                    $targets[] = 'director';
                    $targets[] = 'direktur';
                } elseif ($subNorm === 'logistics' || $subNorm === 'logistik') {
                    $targets[] = 'logistics';
                    $targets[] = 'logistik';
                } elseif ($subNorm === 'finance' || $subNorm === 'keuangan') {
                    $targets[] = 'finance';
                    $targets[] = 'keuangan';
                }
            }
        }

        foreach ($myAliases as $a) {
            if (in_array($a, $targets, true)) {
                return true;
            }
        }
        return false;
    }
}

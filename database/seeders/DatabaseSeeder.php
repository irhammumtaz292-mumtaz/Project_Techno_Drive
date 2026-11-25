<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);

        // Pastikan akun Direktur tersedia (sesuai spesifikasi teknis)
        User::updateOrCreate(
            ['email' => 'direktur@technodrive.test'],
            [
                'name'     => 'Direktur',
                'password' => Hash::make('password'),
                'role'     => 'direktur',
            ]
        );
    }
}

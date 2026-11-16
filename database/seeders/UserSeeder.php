<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Supervisor',
            'email' => 'supervisor@technodrive.com',
            'password' => bcrypt('password123'),
            'role' => 'Supervisor',
        ]);

        \App\Models\User::create([
            'name' => 'Director',
            'email' => 'director@technodrive.com',
            'password' => bcrypt('password123'),
            'role' => 'Director',
        ]);

        \App\Models\User::create([
            'name' => 'Teknisi Handal',
            'email' => 'staff@technodrive.com',
            'password' => bcrypt('password123'),
            'role' => 'Staff',
        ]);
        
        \App\Models\User::create([
            'name' => 'Tim Finance',
            'email' => 'finance@technodrive.com',
            'password' => bcrypt('password123'),
            'role' => 'Finance',
        ]);

        \App\Models\User::create([
            'name' => 'Tim Logistics',
            'email' => 'logistik@technodrive.com',
            'password' => bcrypt('password123'),
            'role' => 'Logistics',
        ]);
    }
}

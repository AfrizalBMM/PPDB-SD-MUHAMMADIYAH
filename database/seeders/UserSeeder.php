<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Superadmin',
            'email' => 'admin-sdm@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin'
        ]);

        User::create([
            'name' => 'Keuangan',
            'email' => 'keu-sdm@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'keuangan'
        ]);
    }
}

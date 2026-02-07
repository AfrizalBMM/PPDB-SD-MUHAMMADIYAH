<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin-sdm@gmail.com'],
            [
                'name' => 'Super Admin PPDB',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
            ]
        );
    }
}

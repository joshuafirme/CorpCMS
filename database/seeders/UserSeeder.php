<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserRole::create([
            'name' => 'Admin',
            'permissions' => json_encode(['dashboard', 'users', 'roles']),
            'status' => 1
        ]);
        User::create([
            'name' => "Josh",
            'email' => "corp_cms@gmail.com",
            'password' => Hash::make("12345"),
            'role_id' => 1,
        ]);
    }
}

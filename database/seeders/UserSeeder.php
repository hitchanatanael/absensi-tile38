<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id_role' => 1,
                'nama' => 'Admin User',
                'email' => 'admin@gmail.com',
                'nik' => '12345667890',
                'password' => Hash::make('password'),
                'foto_user' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_role' => 2,
                'nama' => 'Natanael',
                'email' => 'nata@gmail.com',
                'nik' => '2003113227',
                'password' => Hash::make('password'),
                'foto_user' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_role' => 2,
                'nama' => 'Rahmad Yandi',
                'email' => 'yandi@gmail.com',
                'nik' => '2003113228',
                'password' => Hash::make('password'),
                'foto_user' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_role' => 2,
                'nama' => 'Pangidoan Nugroho',
                'email' => 'edo@gmail.com',
                'nik' => '2003113229',
                'password' => Hash::make('password'),
                'foto_user' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

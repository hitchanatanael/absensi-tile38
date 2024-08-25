<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id_role'    => 1,
                'nama'       => 'Admin',
                'email'      => 'admin@gmail.com',
                'password'   => Hash::make('admin123'),
                'foto_user'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

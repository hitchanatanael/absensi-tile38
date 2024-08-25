<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        foreach (range(1, 10) as $index) {
            DB::table('dosens')->insert([
                'nama'    => $faker->name,
                'nik'     => $faker->unique()->numerify('################'),
                'alamat'  => $faker->address,
                'no_hp'   => $faker->phoneNumber,
            ]);
        }

        DB::table('dosens')->insert([
            'nama'   => 'Hitcha Natanael',
            'nik'    => '1402081204020001',
            'alamat' => 'Jl. Bangau Sakti',
            'no_hp'  => '082285774472',
        ]);
    }
}

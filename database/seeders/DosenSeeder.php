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

        foreach (range(1, 20) as $index) {
            DB::table('dosens')->insert([
                'nama' => $faker->name,
                'nik' => $faker->unique()->numerify('################'), // 16 digit NIK
                'alamat' => $faker->address,
                'no_hp' => $faker->phoneNumber,
            ]);
        }
    }
}

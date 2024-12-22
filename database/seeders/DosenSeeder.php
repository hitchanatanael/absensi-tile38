<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('dosens')->insert([
            [
                'nama'   => 'AIDIL FITRIANSYAH',
                'nip'    => '197809052003121002',
            ],

            [
                'nama'   => 'AL AMINUDDIN',
                'nip'    => '198901262019031006',
            ],
            [
                'nama'   => 'ALFIRMAN',
                'nip'    => '19800305200501100',
            ],
            [
                'nama'   => 'ASTRIED',
                'nip'    => '197810092005012002',
            ],
            [
                'nama'   => 'ELFIZAR',
                'nip'    => '197403271997021002',
            ],
            [
                'nama'   => 'EVFI MAHDIYAH',
                'nip'    => '197502152001122002',
            ],
            [
                'nama'   => 'FATAYAT',
                'nip'    => '197907082005012002',
            ],
            [
                'nama'   => 'GITA SASTRIA',
                'nip'    => '198004292008121002',
            ],
            [
                'nama'   => 'IBNU DAQIQIL ID',
                'nip'    => '198603202015041001',
            ],
            [
                'nama'   => 'JOKO RISANTO',
                'nip'    => '196910302003121002',
            ],
            [
                'nama'   => 'RAHMAD KURNIAWAN',
                'nip'    => '199001312022031007',
            ],
            [
                'nama'   => 'RIKI ARIO NUGROHO',
                'nip'    => '198501232019031006',
            ],
            [
                'nama'   => 'RONI SALAMBUE',
                'nip'    => '197409302003121001',
            ],
            [
                'nama'   => 'SONYA MEITARICE',
                'nip'    => '198905042022032005',
            ],
            [
                'nama'   => 'SUKAMTO',
                'nip'    => '196403041991031003',
            ],
            [
                'nama'   => 'TISHA MELIA',
                'nip'    => '198403082022032001',
            ],
            [
                'nama'   => 'YANTI ANDRIYANI',
                'nip'    => '198105122008122001',
            ],
            [
                'nama'   => 'ZAIFUL BAHRI',
                'nip'    => '196312311997021001',
            ],
            [
                'nama'   => 'ZUL INDRA',
                'nip'    => '198805032022031003',
            ],
        ]);
    }
}

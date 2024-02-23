<?php

namespace Database\Seeders;

use App\Models\Divisi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Divisi::create([
            'nama_divisi' => 'Produksi',
            'id_client'   => '2'
        ]);
        Divisi::create([
            'nama_divisi' => 'MPO',
            'id_client'   => '1'
        ]);

        Divisi::create([
            'nama_divisi' => 'Finance',
            'id_client'   => '1'
        ]);

        Divisi::create([
            'nama_divisi' => 'General Affair ( GA )',
            'id_client'   => '1'
        ]);

        Divisi::create([
            'nama_divisi' => 'Human Resource Development',
            'id_client'   => '1'
        ]);

        Divisi::create([
            'nama_divisi' => 'Direktur',
            'id_client'   => '1'
        ]);
    }

}

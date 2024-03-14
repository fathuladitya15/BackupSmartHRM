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
        // DIVISI PFI
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

        $orbitin = Divisi::create([
            'nama_divisi' => 'Orbitin',
            'id_client'     => 1
        ]);
        $ddm = Divisi::create([
            'nama_divisi' => 'Digital Development Manager',
            'id_client'   => 1
        ]);

        // DIVISI MEGASARI
        Divisi::create([
            'nama_divisi' => 'Produksi',
            'id_client'   => '2'
        ]);

        // DIVISI AIO SENTUL
        Divisi::create([
            'nama_divisi' => 'GA',
            'id_client'   => '3'
        ]);
        Divisi::create([
            'nama_divisi' => 'PODUKSI',
            'id_client'   => '3'
        ]);
        Divisi::create([
            'nama_divisi' => 'LOGISTIK',
            'id_client'   => '3'
        ]);
    }

}

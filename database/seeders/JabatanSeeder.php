<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JabatanSeeder extends Seeder
{

    public function run(): void
    {

        Jabatan::create([
            'nama_jabatan' => 'Supervisor',
            'id_client'    => '2'
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Koordinator Lapangan',
            'id_client'    => '2'
        ]);


        Jabatan::create([
            'nama_jabatan' => 'Supervisor',
            'id_client'    => '3'
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Supervisor',
            'id_client'    => '1'
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Koordinator Lapangan',
            'id_client'    => '1'
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Manager',
            'id_client'    => '1'
        ]);

        Jabatan::create([
            'nama_jabatan' => 'Direktur',
            'id_client'    => '1'
        ]);
        Jabatan::create([
            'nama_jabatan' => 'Karyawan',
            'id_client'    => '1'
        ]);
    }
}

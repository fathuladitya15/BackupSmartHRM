<?php

namespace Database\Seeders;

use App\Models\Clients;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Clients::create([
            'nama_client' => 'PT Proven Force Indonesia',
            'prov' => "DKI Jakarta",
            'Kota' => "Jakarata Selatan",
            'Kec'  => "Cilandak",
            'Kel'  => "C",
            'alamat_detail' => "Tower Alamanda"
        ]);

        Clients::create([
            'nama_client' => 'PT Godrej Indonesia',
            'prov' => 'Jawa Barat',
            'kota' => 'Kab Bogor',
            'kec' => 'Gunung  Putri',
            'kel' => 'Gunung Putri',
            'alamat_detail' => 'Gunung Putri',
        ]);

        Clients::create([
            'nama_client' => 'PT Amerta Indah Otsuka ( Sukabumi )',
            'prov' => 'Jawa Barat',
            'kota' => 'Sukabumi',
            'kec' => 'Kutajaya',
            'kel' => 'Cicurug',
            'alamat_detail' => 'Jl. siliwangi No. KM 28',
        ]);

        Clients::create([
            'nama_client' => 'PT Amerta Indah Otsuka ( Sentul )',
            'prov' => 'Jawa Barat',
            'kota' => 'Bogor - Sentul',
            'kec' => 'Cibinong',
            'kel' => 'Bogor',
            'alamat_detail' => 'Jl. siliwangi No. KM 28',
        ]);

        Clients::create([
            'nama_client' => 'PT Ceres',
            'prov' => 'Jawa Barat',
            'kota' => 'Bekasi',
            'kec' => 'Bantar Gebang',
            'kel' => ' Bantargebang',
            'alamat_detail' => 'Jl. Raya Narogong KM.7, RT.005/RW.001',
        ]);

        Clients::create([
            'nama_client' => 'PT MAS',
            'prov' => 'Null',
            'kota' => 'Null',
            'kec' => 'Null',
            'kel' => ' Null',
            'alamat_detail' => 'Null',
        ]);

        Clients::create([
            'nama_client' => 'PT INDOLOK',
            'prov' => 'Null',
            'kota' => 'Null',
            'kec' => 'Null',
            'kel' => ' Null',
            'alamat_detail' => 'Null',
        ]);

        Clients::create([
            'nama_client' => 'PT Yupi Indo Jelly Gum',
            'prov' => 'Null',
            'kota' => 'Null',
            'kec' => 'Null',
            'kel' => ' Null',
            'alamat_detail' => 'Null',
        ]);
    }
}

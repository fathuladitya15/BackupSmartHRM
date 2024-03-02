<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use KategoriCuti;

class KategoriCutiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KategoriCuti::create([
            'nama_kategori' => 'Cuti Melahirkan'
        ]);
        KategoriCuti::create([
            'nama_kategori' => 'Cuti Hamil'
        ]);
    }
}

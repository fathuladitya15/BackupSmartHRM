<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Filemanager;

class FilemanagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Filemanager::create([
            'filename' => '22001TTD.svg',
            'path'     => 'assets/img/signature/22001TTD.svg',
            'extension'  => 'svg',
            'id_karyawan' => '22001',
            'slug'      =>'signature',
            'keterangan' => 'Tanda Tangan Mamat Alkatiri',
        ]);
    }
}

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

        Filemanager::create([
            'filename' => '1999123TTD.svg',
            'path'     => 'assets/img/signature/1999123TTD.svg',
            'extension'  => 'svg',
            'id_karyawan' => '1999123',
            'slug'      =>'signature',
            'keterangan' => 'Tanda Tangan Rasya Bayu Pamungkas',
        ]);

        Filemanager::create([
            'filename' => 'B0483TTD.svg',
            'path'     => 'assets/img/signature/B0483TTD.svg',
            'extension'  => 'svg',
            'id_karyawan' => 'B0483',
            'slug'      =>'signature',
            'keterangan' => 'Tanda Tangan Achmad Siswanto',
        ]);

        Filemanager::create([
            'filename' => 'PFI00123TTD.svg',
            'path'     => 'assets/img/signature/PFI00123TTD.svg',
            'extension'  => 'svg',
            'id_karyawan' => 'PFI00123',
            'slug'      =>'signature',
            'keterangan' => 'Tanda Tangan REHAN ERLANGGA',
        ]);
        Filemanager::create([
            'filename' => 'AIO_5202402281458.pdf',
            'path'     => '/filemanager/file_izin/AIO_5202402281458.pdf',
            'extension'  => 'pdf',
            'id_karyawan' => 'B0483',
            'slug'      =>'izin',
            'keterangan' => 'FILE IZIN Achmad Siswanto',
        ]);
    }
}

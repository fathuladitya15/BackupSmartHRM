<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Izin;

class IzinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Izin::create([
            'karyawan_id'       => '22001',
            'nama_karyawan'     => 'Mamat Alkatiri',
            'divisi'            => 'Produksi',
            'jabatan'           => 'Pakcing',
            'alasan'            => 'Dinas diluar',
            'tanggal_pembuatan' => '2024-02-27',
            'ttd_karyawan'      => 'assets/img/signature/22001TTD.svg',
            'kembali'           => 0,
            'id_client'         => 2,
            'status'            => 0,
            'detail'            => "TESS",
            'jam_keluar'        => '14:00'
        ]);

        Izin::create([
            'karyawan_id'       => '22001',
            'nama_karyawan'     => 'Mamat Alkatiri',
            'divisi'            => 'Produksi',
            'jabatan'           => 'Pakcing',
            'alasan'            => 'Pribadi',
            'tanggal_pembuatan' => '2024-02-25',
            'ttd_karyawan'      => 'assets/img/signature/22001TTD.svg',
            'kembali'           => 1,
            'id_client'         => 2,
            'status'            => 0,
            'detail'            => "TESS 22",
            'jam_keluar'        => '13:00'
        ]);

        Izin::create([
            'karyawan_id'       => 'B0483',
            'nama_karyawan'     => 'Achmad Siswanto',
            'divisi'            => 'GA',
            'jabatan'           => 'Pakcing',
            'alasan'            => 'Operasional',
            'tanggal_pembuatan' => '2024-02-02',
            'ttd_karyawan'      => 'assets/img/signature/B0483TTD.svg',
            'ttd_mengetahui'    => 'assets/img/signature/PFI00123TTD.svg',
            'id_client'         => 3,
            'alasan'            => "TESTING 1",
            'status'            => 2,
            'id_filemanager'    => 5,
            'user_id_mengetahui'=> 'REHAN ERLANGGA',
            'jam_keluar'        => '14:00'
        ]);
    }
}

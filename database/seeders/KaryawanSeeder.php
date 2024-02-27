<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Karyawan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Karyawan::create([
            'nama_karyawan' => 'Mamat Alkatiri',
            'id_karyawan'   => 22001,
            'no_hp'         => "08892254399",
            'tanggal_lahir' => "1993-10-10",
            'tempat_lahir'  => "Subang",
            'jenis_kelamin' => "L",
            'nik'           => 3201301502010091,
            'no_kk'         => 0,
            'alamat'        =>"Jakarta",
            'alamat_domisili' => 'Jakarta',
            'lokasi_kerja'  => 2,
            'divisi'        => 1,
            'jabatan'       => 1,
            'join_date'     => "2023-10-10",
            'end_date'      => "2024-10-10",
            'gol_karyawan'  => 'KHL',
            'marital'       => "TK",
            'nama_bank'     => "BANK SYARIAH INDONESIA (Eks BNI SYARIAH)",
            'cabang_bank'   => "Jakarta",
            'kd_bank'       => 427,
            'norek_bank'    =>112233,
            'status'        => 0,
        ]);
    }
}

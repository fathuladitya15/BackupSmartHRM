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
        // KARYAWAN MEGASARI
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
            'divisi'        => 6,
            'jabatan'       => 7,
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
        Karyawan::create([
            'nama_karyawan' => 'Rasya Bayu Pamungkas',
            'id_karyawan'   => 1999123,
            'no_hp'         => "088922543991",
            'tanggal_lahir' => "1993-10-10",
            'tempat_lahir'  => "Subang",
            'jenis_kelamin' => "L",
            'nik'           => 3201301502010099,
            'no_kk'         => 0,
            'alamat'        =>"Jakarta",
            'alamat_domisili' => 'Jakarta',
            'lokasi_kerja'  => 2,
            'divisi'        => 1,
            'jabatan'       => 3,
            'join_date'     => "2023-10-10",
            'end_date'      => "2027-10-10",
            'gol_karyawan'  => 'PKWT',
            'marital'       => "TK",
            'nama_bank'     => "BANK SYARIAH INDONESIA (Eks BNI SYARIAH)",
            'cabang_bank'   => "Jakarta",
            'kd_bank'       => 427,
            'norek_bank'    => 77987321,
            'status'        => 0,
        ]);

        // KARYAWAN AIO SUKABUMI

        Karyawan::create([
            'nama_karyawan' => 'REHAN ERLANGGA',
            'id_karyawan'   => 'PFI00123',
            'no_hp'         => "08892254307",
            'tanggal_lahir' => "1993-10-10",
            'tempat_lahir'  => "Bogor",
            'jenis_kelamin' => "L",
            'nik'           => 3201301502010191,
            'no_kk'         => 0,
            'alamat'        =>"Jakarta",
            'alamat_domisili' => 'Jakarta',
            'lokasi_kerja'  => 3,
            'divisi'        => 1,
            'jabatan'       => 3,
            'join_date'     => "2023-10-10",
            'end_date'      => "2024-10-10",
            'gol_karyawan'  => 'PKWT',
            'marital'       => "TK",
            'nama_bank'     => "BANK SYARIAH INDONESIA (Eks BNI SYARIAH)",
            'cabang_bank'   => "Jakarta",
            'kd_bank'       => 427,
            'norek_bank'    =>5554442255,
            'status'        => 0,
        ]);
        Karyawan::create([
            'nama_karyawan' => 'Achmad Siswanto',
            'id_karyawan'   => 'B0483',
            'no_hp'         => "087797768863
            ",
            'tanggal_lahir' => "1993-10-10",
            'tempat_lahir'  => "Subang",
            'jenis_kelamin' => "L",
            'nik'           => 3201301502010099,
            'no_kk'         => 0,
            'alamat'        =>"Kp. Babakan Rt 003 Rw 008 Ds. Cimahpar Kec. Bogor Utara Kota Bogor",
            'alamat_domisili' => 'Kp. Babakan Rt 003 Rw 008 Ds. Cimahpar Kec. Bogor Utara Kota Bogor',
            'lokasi_kerja'  => 3,
            'divisi'        => 7,
            'jabatan'       => 8,
            'join_date'     => "2023-10-10",
            'end_date'      => "2027-10-10",
            'gol_karyawan'  => 'PKWT',
            'marital'       => "TK",
            'nama_bank'     => "BANK BRI (BANK RAKYAT INDONESIA)",
            'cabang_bank'   => "Jakarta",
            'kd_bank'       => 002,
            'norek_bank'    => 135501018134502,
            'status'        => 0,
            'no_sio'        =>3271051108690015,
            'cuti'          => 0,
            'no_npwp'       => '59.560.014.9-404.000',
            'no_kpj'        => '20092523230',

        ]);
    }
}

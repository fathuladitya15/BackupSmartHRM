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
            'nama_karyawan'     => 'Raihan Erlangga',
            'id_karyawan'           => 'AK001',
            'no_hp'                 => '08892254399',
            'tanggal_lahir'         => Carbon::parse('12-06-1990')->format("Y-m-d"),
            'tempat_lahir'          => 'Jakarta',
            'jenis_kelamin'         => 'Jakarta',
            'nik'                   => '3201301502010002',
            'no_kk'                 => '3201301502010001',
            'alamat'                => 'Bogor',
            'alamat_domisili'       => 'Bogor',
            'lokasi_kerja'          => 2,
            'divisi'                => 1,
            'jabatan'               => 2,
            'join_date'             => '2020-10-10',
            'end_date'              => '2025-10-10',
            'no_sio'                => 0,
            'gol_karyawan'          => 'PKWT',
            'marital'               => 'TK',
            'tJabatan'              => '100000',
            'tTransport'            => '100000',
            'nama_bank'             => 'BCA',
            'cabang_bank'           => 'Bogor',
            'kd_bank'               => '014',
            'norek_bank'            => '1740590797',
            'status'                => 0,
            'status_karyawan'       => 0,
            'cuti'                  => 1,
            'usia'                  => 23,
            'nama_ibu'              => 'Neneng',
            'nama_bapa'             => 'Abdul',
            'pendidikan'            => 'SMP',
            'agama'                 => 'ISLAM',
            'kategori'              => 'project',

        ]);

        Karyawan::create([
            'nama_karyawan'         => 'Diw',
            'id_karyawan'           => 'HRD001',
            'no_hp'                 => '08892254399',
            'tanggal_lahir'         => Carbon::parse('12-06-1990')->format("Y-m-d"),
            'tempat_lahir'          => 'Jakarta',
            'jenis_kelamin'         => 'Jakarta',
            'nik'                   => '3201301502010002',
            'no_kk'                 => '3201301502010001',
            'alamat'                => 'Bogor',
            'alamat_domisili'       => 'Bogor',
            'lokasi_kerja'          => 1,
            'divisi'                => 1,
            'jabatan'               => 3,
            'join_date'             => '2020-10-10',
            'end_date'              => '2025-10-10',
            'no_sio'                => 0,
            'gol_karyawan'          => 'PKWT',
            'marital'               => 'TK',
            'tJabatan'              => '100000',
            'tTransport'            => '100000',
            'nama_bank'             => 'BCA',
            'cabang_bank'           => 'Bogor',
            'kd_bank'               => '014',
            'norek_bank'            => '1740590797',
            'status'                => 0,
            'status_karyawan'       => 0,
            'cuti'                  => 1,
            'usia'                  => 23,
            'nama_ibu'              => 'Neneng',
            'nama_bapa'             => 'Abdul',
            'pendidikan'            => 'SMP',
            'agama'                 => 'ISLAM',
            'kategori'              => 'project',

        ]);

        Karyawan::create([
            'nama_karyawan'         => 'Rasya Bayu Pamungkas',
            'id_karyawan'           => 'SPV001',
            'no_hp'                 => '08892254399',
            'tanggal_lahir'         => Carbon::parse('12-06-1990')->format("Y-m-d"),
            'tempat_lahir'          => 'Jakarta',
            'jenis_kelamin'         => 'Jakarta',
            'nik'                   => '3201301502010002',
            'no_kk'                 => '3201301502010001',
            'alamat'                => 'Bogor',
            'alamat_domisili'       => 'Bogor',
            'lokasi_kerja'          => 2,
            'divisi'                => 1,
            'jabatan'               => 1,
            'join_date'             => '2020-10-10',
            'end_date'              => '2025-10-10',
            'no_sio'                => 0,
            'gol_karyawan'          => 'PKWT',
            'marital'               => 'TK',
            'tJabatan'              => '100000',
            'tTransport'            => '100000',
            'nama_bank'             => 'BCA',
            'cabang_bank'           => 'Bogor',
            'kd_bank'               => '014',
            'norek_bank'            => '1740590797',
            'status'                => 0,
            'status_karyawan'       => 0,
            'cuti'                  => 1,
            'usia'                  => 23,
            'nama_ibu'              => 'Neneng',
            'nama_bapa'             => 'Abdul',
            'pendidikan'            => 'SMP',
            'agama'                 => 'ISLAM',
            'kategori'              => 'project',

        ]);

        Karyawan::create([
            'nama_karyawan'         => 'Muhamad Fathul Aditya',
            'id_karyawan'           => 'KRPST25616',
            'no_hp'                 => '08892254399',
            'tanggal_lahir'         => Carbon::parse('12-06-1990')->format("Y-m-d"),
            'tempat_lahir'          => 'Jakarta',
            'jenis_kelamin'         => 'Jakarta',
            'nik'                   => '3201301502010002',
            'no_kk'                 => '3201301502010001',
            'alamat'                => 'Bogor',
            'alamat_domisili'       => 'Bogor',
            'lokasi_kerja'          => 1,
            'divisi'                => 1,
            'jabatan'               => 1,
            'join_date'             => '2020-10-10',
            'end_date'              => '2025-10-10',
            'no_sio'                => 0,
            'gol_karyawan'          => 'PKWT',
            'marital'               => 'TK',
            'tJabatan'              => '100000',
            'tTransport'            => '100000',
            'nama_bank'             => 'BCA',
            'cabang_bank'           => 'Bogor',
            'kd_bank'               => '014',
            'norek_bank'            => '1740590797',
            'status'                => 0,
            'status_karyawan'       => 0,
            'cuti'                  => 1,
            'usia'                  => 23,
            'nama_ibu'              => 'Neneng',
            'nama_bapa'             => 'Abdul',
            'pendidikan'            => 'SMP',
            'agama'                 => 'ISLAM',
            'kategori'              => 'project',

        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Absensi;

class AbsesnsiSeader extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $absen_tgl_1 = Absensi::create([
            'id_karyawan'                   => 'PFI18723111',
            'nama_karyawan'                 => 'MANAGER FINANCE',
            'divisi'                        => 'Finance',
            'jabatan'                       => 'Manager',
            'tanggal'                       => '2024-03-04',
            'jam_masuk'                     => '08.00',
            'lokasi_absen_masuk'            => 'Alamada Tower',
            'detail_lokasi_absen_masuk'     => 'Jl. TB Simatupang No.22-26, RT.1/RW.1, Cilandak Bar., Kec. Cilandak, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12430',
            'jam_keluar'                    => '15.30',
            'shift'                         => 'non shift',
            'lokasi_absen_plg'              => 'Alamada Tower',
            'detail_lokasi_absen_plg'       => 'Jl. TB Simatupang No.22-26, RT.1/RW.1, Cilandak Bar., Kec. Cilandak, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12430',
        ]);

        $absen_tgl_2 = Absensi::create([
            'id_karyawan'                   => 'PFI18723111',
            'nama_karyawan'                 => 'MANAGER FINANCE',
            'divisi'                        => 'Finance',
            'jabatan'                       => 'Manager',
            'tanggal'                       => '2024-03-05',
            'jam_masuk'                     => '08.00',
            'lokasi_absen_masuk'            => 'Alamada Tower',
            'detail_lokasi_absen_masuk'     => 'Jl. TB Simatupang No.22-26, RT.1/RW.1, Cilandak Bar., Kec. Cilandak, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12430',
            'jam_keluar'                    => '15.30',
            'shift'                         => 'non shift',
            'lokasi_absen_plg'              => 'Alamada Tower',
            'detail_lokasi_absen_plg'       => 'Jl. TB Simatupang No.22-26, RT.1/RW.1, Cilandak Bar., Kec. Cilandak, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12430',
        ]);

        $absen_tgl_3 = Absensi::create([
            'id_karyawan'                   => 'PFI18723111',
            'nama_karyawan'                 => 'MANAGER FINANCE',
            'divisi'                        => 'Finance',
            'jabatan'                       => 'Manager',
            'tanggal'                       => '2024-03-06',
            'jam_masuk'                     => '08.00',
            'lokasi_absen_masuk'            => 'Alamada Tower',
            'detail_lokasi_absen_masuk'     => 'Jl. TB Simatupang No.22-26, RT.1/RW.1, Cilandak Bar., Kec. Cilandak, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12430',
            'jam_keluar'                    => '15.30',
            'shift'                         => 'non shift',
            'lokasi_absen_plg'              => 'Alamada Tower',
            'detail_lokasi_absen_plg'       => 'Jl. TB Simatupang No.22-26, RT.1/RW.1, Cilandak Bar., Kec. Cilandak, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12430',
        ]);

        $absen_tgl_4 = Absensi::create([
            'id_karyawan'                   => 'PFI00123',
            'nama_karyawan'                 => 'REHAN ERLANGGA',
            'divisi'                        => 'Finance',
            'jabatan'                       => 'Admin',
            'tanggal'                       => '2024-03-04',
            'jam_masuk'                     => '07.50',
            'lokasi_absen_masuk'            => 'PT Amerta Indah Otsuka ( Sukabumi )',
            'detail_lokasi_absen_masuk'     => 'Jl. Siliwangi No.KM. 28, Kutajaya, Kec. Cicurug, Kabupaten Sukabumi, Jawa Barat 43359',
            'jam_keluar'                    => '15.45',
            'shift'                         => 'non shift',
            'lokasi_absen_plg'              => 'PT Amerta Indah Otsuka ( Sukabumi )',
            'detail_lokasi_absen_plg'       => 'Jl. Siliwangi No.KM. 28, Kutajaya, Kec. Cicurug, Kabupaten Sukabumi, Jawa Barat 43359',
        ]);

        $absen_tgl_5= Absensi::create([
            'id_karyawan'                   => 'PFI00123',
            'nama_karyawan'                 => 'REHAN ERLANGGA',
            'divisi'                        => 'MPO',
            'jabatan'                       => 'Admin',
            'tanggal'                       => '2024-03-05',
            'jam_masuk'                     => '08.00',
            'lokasi_absen_masuk'            => 'PT Amerta Indah Otsuka ( Sukabumi )',
            'detail_lokasi_absen_masuk'     => 'Jl. Siliwangi No.KM. 28, Kutajaya, Kec. Cicurug, Kabupaten Sukabumi, Jawa Barat 43359',
            'jam_keluar'                    => '15.30',
            'shift'                         => 'non shift',
            'lokasi_absen_plg'              => 'PT Amerta Indah Otsuka ( Sukabumi )',
            'detail_lokasi_absen_plg'       => 'Jl. Siliwangi No.KM. 28, Kutajaya, Kec. Cicurug, Kabupaten Sukabumi, Jawa Barat 43359',
        ]);

        $absen_tgl_6 = Absensi::create([
            'id_karyawan'                   => 'PFI00123',
            'nama_karyawan'                 => 'REHAN ERLANGGA',
            'divisi'                        => 'MPO',
            'jabatan'                       => 'Admin',
            'tanggal'                       => '2024-03-06',
            'jam_masuk'                     => '08.00',
            'lokasi_absen_masuk'            => 'PT Amerta Indah Otsuka ( Sukabumi )',
            'detail_lokasi_absen_masuk'     => 'Jl. Siliwangi No.KM. 28, Kutajaya, Kec. Cicurug, Kabupaten Sukabumi, Jawa Barat 43359',
            'jam_keluar'                    => '15.30',
            'shift'                         => 'non shift',
            'lokasi_absen_plg'              => 'PT Amerta Indah Otsuka ( Sukabumi )',
            'detail_lokasi_absen_plg'       => 'Jl. Siliwangi No.KM. 28, Kutajaya, Kec. Cicurug, Kabupaten Sukabumi, Jawa Barat 43359',
        ]);

        $absen_tgl_1_m = Absensi::create([
            'id_karyawan'                   => '22001',
            'nama_karyawan'                 => 'Mamat Alkatiri',
            'divisi'                        => 'Produksi',
            'jabatan'                       => 'Pakcing',
            'tanggal'                       => '2024-03-04',
            'jam_masuk'                     => '06.55',
            'lokasi_absen_masuk'            => 'PT Megasari Makmur 3',
            'detail_lokasi_absen_masuk'     => 'HW5M+F88, Cicadas, Kec. Gn. Putri, Kabupaten Bogor, Jawa Barat 16964',
            'jam_keluar'                    => '15.05',
            'shift'                         => '1',
            'lokasi_absen_plg'              => 'PT Megasari Makmur 3',
            'detail_lokasi_absen_plg'       => 'HW5M+F88, Cicadas, Kec. Gn. Putri, Kabupaten Bogor, Jawa Barat 16964',
            'id_client'                     => 2,

        ]);
        $absen_tgl_2_m = Absensi::create([
            'id_karyawan'                   => '22001',
            'nama_karyawan'                 => 'Mamat Alkatiri',
            'divisi'                        => 'Produksi',
            'jabatan'                       => 'Pakcing',
            'tanggal'                       => '2024-03-05',
            'jam_masuk'                     => '06.55',
            'lokasi_absen_masuk'            => 'PT Megasari Makmur 3',
            'detail_lokasi_absen_masuk'     => 'HW5M+F88, Cicadas, Kec. Gn. Putri, Kabupaten Bogor, Jawa Barat 16964',
            'jam_keluar'                    => '15.05',
            'shift'                         => '1',
            'lokasi_absen_plg'              => 'PT Megasari Makmur 3',
            'detail_lokasi_absen_plg'       => 'HW5M+F88, Cicadas, Kec. Gn. Putri, Kabupaten Bogor, Jawa Barat 16964',
            'id_client'                     => 2,
        ]);
        $absen_tgl_3_m = Absensi::create([
            'id_karyawan'                   => '22001',
            'nama_karyawan'                 => 'Mamat Alkatiri',
            'divisi'                        => 'Produksi',
            'jabatan'                       => 'Pakcing',
            'tanggal'                       => '2024-03-06',
            'jam_masuk'                     => '06.55',
            'lokasi_absen_masuk'            => 'PT Megasari Makmur 3',
            'detail_lokasi_absen_masuk'     => 'HW5M+F88, Cicadas, Kec. Gn. Putri, Kabupaten Bogor, Jawa Barat 16964',
            'jam_keluar'                    => '15.05',
            'shift'                         => '1',
            'lokasi_absen_plg'              => 'PT Megasari Makmur 3',
            'detail_lokasi_absen_plg'       => 'HW5M+F88, Cicadas, Kec. Gn. Putri, Kabupaten Bogor, Jawa Barat 16964',
            'id_client'                     => 2,

        ]);

    }
}

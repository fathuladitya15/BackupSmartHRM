<?php

namespace App\Imports;

use Auth;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Divisi;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KaryawanImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $cek_jabatan = Jabatan::where('nama_jabatan','LIKE','%'.$row[12].'%')->where("id_client",Auth::user()->id_client);
        $jabatan     = $cek_jabatan->count() > 0 ? $cek_jabatan->first()->id : "" ;

        $cek_divisi = Divisi::where('nama_divisi','LIKE','%'.$row[13].'%')->where("id_client",Auth::user()->id_client);
        $divisi     = $cek_divisi->count() > 0 ? $cek_divisi->first()->id : "" ;

        $insert = [
            'id_karyawan'           => $row[0],
            'nama_karyawan'         => $row[1],
            'no_hp'                 => $row[2],
            'tanggal_lahir'         => $row[3],
            'tempat_lahir'          => $row[4],
            'nik'                   => $row[5],
            'alamat'                => $row[6],
            'alamat_domisili'       => $row[7],
            'no_npwp'               => $row[8],
            'no_sio'                => $row[9],
            'gol_karyawan'          => $row[10],
            'marital'               => $row[11],
            'jabatan'               => $jabatan,
            'divisi'                => $divisi,
            'norek_bank'            => $row[14],
            'nama_bank'             => $row[15],
            'no_kpj'                => $row[16],
            'no_jkn'                => $row[17],
            'cuti'                  => $row[18],
            'join_date'             => $row[19],
            'end_date'              => $row[20],
            'lokasi_kerja'          => Auth::user()->id_client,
            'status'                => 0,
        ];


        return new Karyawan($insert);
    }
}

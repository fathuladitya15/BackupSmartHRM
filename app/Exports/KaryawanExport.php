<?php

namespace App\Exports;

use DB;
use Auth;
use App\Models\Karyawan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KaryawanExport implements  FromQuery, WithHeadings
{

    public function query()
    {
        return DB::table('users as us')
        ->select('us.id_karyawan','us.name','kr.no_hp','kr.tanggal_lahir','kr.tempat_lahir','kr.nik','kr.alamat','kr.alamat_domisili','kr.no_npwp','kr.no_sio','kr.gol_karyawan','kr.marital','tj.nama_jabatan','td.nama_divisi','kr.norek_bank','kr.nama_bank','kr.no_kpj','kr.no_jkn','kr.cuti','kr.join_date','kr.end_date')
        ->join('table_karyawan as kr','kr.id_karyawan','=','us.id_karyawan')
        ->join('table_divisi as td','td.id','=','kr.divisi')
        ->join('table_jabatan as tj','tj.id','=','kr.jabatan')
        ->where('us.id_client',Auth::user()->id_client)
        ->where('us.roles','karyawan')
        ->orderBy('us.id_karyawan')
        ->take(1);
    }

    public function headings(): array
    {
        return [
            'Id Karyawan',
            'Nama Karyawan',
            'No Handphone',
            'Tanggal Lahir',
            'Tempat Lahir',
            'NIK',
            'Alamat',
            'Alamat Domisili',
            'NPWP',
            'NO SIO',
            'Status Karyawan',
            'Marital',
            'Jabatan',
            'Divisi',
            'No. Rekening',
            'Bank',
            'KPJ',
            'JKN',
            'cuti',
            'Join Date',
            'End Date'
        ];
    }
}

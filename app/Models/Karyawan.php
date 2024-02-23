<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'table_karyawan';

    protected $fillable = [
        'nama_karyawan',
        'id_karyawan',
        'no_hp',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'nik',
        'no_kk',
        'alamat',
        'alamat_domisili',
        'lokasi_kerja',
        'divisi',
        'jabatan',
        'join_date',
        'end_date',
        'no_sio',
        'gol_karyawan',
        'marital',
        'tJabatan',
        'tTransport',
        'nama_bank',
        'cabang_bank',
        'kd_bank',
        'norek_bank',
        'status',
        'status_karyawan',
        'disetujui_oleh',
        'disetujui_pada',
        'cuti',
        'usia',
        'nama_s_i',
        'jmlh_anak',
        'nama_ibu',
        'nama_anak',
        'nama_bapa',
        'pendidikan',
        'agama',
        'kategori',
        'pencairan_bpjstk',
        'no_npwp',
        'no_kpj',
        'no_jkn',
    ];
}

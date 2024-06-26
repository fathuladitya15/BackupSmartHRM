<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    use HasFactory;

    protected $table    = 'table_izin';

    protected $fillable = [
        'no_surat',
        'karyawan_id',
        'nama_karyawan',
        'divisi',
        'jabatan',
        'alasan',
        'detail',
        'tanggal_pembuatan',
        'jam_masuk',
        'jam_keluar',
        'ttd_karyawan',
        'ttd_mengetahui',
        'ttd_hrd',
        'ttd_direktur',
        'user_id_mengetahui',
        'disetujui_pada',
        'disetujui_oleh',
        'kembali',
        'status',
        'id_filemanager',
        'id_client'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    use HasFactory;

    protected $table    = 'table_izin';

    protected $fillable = [
        'karyawan_id',
        'type_waktu',
        'detail',
        'tanggal_pembuatan',
        'jam_masuk',
        'jam_keluar',
        'ttd_karyawan',
        'ttd_mengetahui',
        'user_id_mengetahui',
        'disetujui_pada',
        'disetujui_oleh',
        'status',
        'id_filemanager',
    ];
}

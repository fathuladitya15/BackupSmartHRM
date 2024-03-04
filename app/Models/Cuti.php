<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    protected $table = "table_cuti";

    protected $fillable = [
        'id_karyawan',
        'nama_karyawan',
        'divisi',
        'jabatan',
        'kategori_cuti',
        'alasan',
        'jumlah_cuti',
        'ambil_cuti',
        'start_date',
        'end_date',
        'ttd_karyawan',
        'ttd_manager',
        'ttd_hrd',
        'ttd_direktur',
        'disetujui_oleh',
        'disetujui_pada',
        'status',
    ];
}

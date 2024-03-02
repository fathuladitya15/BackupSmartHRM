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
        'keterangan_cuti',
        'jumlah_cuti',
        'ambil_cuti',
        'ttd_manager',
        'ttd_hrd',
        'ttd_direktur',
        'disetujui_oleh',
    ];
}

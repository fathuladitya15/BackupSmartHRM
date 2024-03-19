<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanSPV extends Model
{
    use HasFactory;
    protected $table = 'table_lap_produksi_to_spv';

    protected $fillable = [
       'id_table_lap_period',
       'total_tagihan',
       'persentase',
       'hasil_persentase',
       'status',
       'disetujui_oleh',
       'disetujui_pada',
    ];
}

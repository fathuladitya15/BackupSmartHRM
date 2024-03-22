<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreOrder extends Model
{
    use HasFactory;

    protected $table = 'table_pre_order';

    protected $fillable = [
       'bulan',
       'no_po',
        'tanggal',
       'divisi',
        'batas_waktu',
       'dibuat_oleh',
       'ttd_pembuat',
       'disetujui_oleh',
       'ttd_direktur',
       'nama_direktur',
        'nama_direktur',
        'id_user',
        'id_client'
    ];

    function barang() {
        return $this->hasMany(BarangPreOrder::class,'id','id_pre_order');
    }
}

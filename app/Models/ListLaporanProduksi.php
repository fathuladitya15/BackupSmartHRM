<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListLaporanProduksi extends Model
{
    use HasFactory;

    protected $table = 'table_lap_period';

    protected $fillable  = [
        'keterangan',
        'from_date',
        'to_date',
        'total_produk',
        'total_tagihan',
        'id_client',
        'status'
    ];
}

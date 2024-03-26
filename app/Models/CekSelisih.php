<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CekSelisih extends Model
{
    use HasFactory;

    protected $table = 'table_selisih_yupi';

    protected $fillable = [
       'id_table_lap_produksi',
       'tanggal',
       'TotalCountProduk_DB',
       'TotalCountProduk_M',
       'status',
       'selisih',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangPreOrder extends Model
{
    use HasFactory;

    protected $table = "table_barang_po";

    protected $fillable = [
      'id_pre_order',
      'nama_barang',
      'unit',
      'satuan',
      'biaya',
      'jumlah',
      'keterangan',
    ];

    function order() {
        $this->hasOne(PreOrder::class,'id_table_pre','id');
    }
}

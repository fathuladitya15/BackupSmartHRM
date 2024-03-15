<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListProduk extends Model
{
    use HasFactory;

    protected $table = 'table_produk';

    protected $fillable = [
        'no_produk',
        'tipe_produk',
        'nama_produk',
        'satuan_produk',
        'harga_produk',
        'id_client',
    ];
}

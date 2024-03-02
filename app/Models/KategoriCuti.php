<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriCuti extends Model
{
    use HasFactory;

    protected $table = 'table_kategori_cuti';

    protected $fillable = [
        'nama_kategori'
    ];
}

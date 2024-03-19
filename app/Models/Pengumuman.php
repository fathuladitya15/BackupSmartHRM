<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'table_pengumuman';
    protected $fillable = [
        'judul',
        'pembuat',
        'keterangan',
        'views',
        'lampiran',
    ];
}

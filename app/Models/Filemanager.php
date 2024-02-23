<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filemanager extends Model
{
    use HasFactory;

    protected $table = 'table_file_manager';

    protected $fillable   = [
        'filename',
        'path',
        'extension',
        'id_karyawan',
        'slug',
        'keterangan',
    ];
}

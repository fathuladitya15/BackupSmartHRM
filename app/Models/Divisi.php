<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;

    protected $table = 'table_divisi';

    protected $fillable = ['nama_divisi','id_client'];

    function karyawan() {
        return $this->hasOne(Karyawan::class,'id','divisi');
    }
}

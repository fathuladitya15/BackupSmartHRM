<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'table_jabatan';

    protected $fillable = ['nama_jabatan','id_client'];

    function karyawan() {
        return $this->hasOne(Karyawan::class,'id','jabatan');
    }
}

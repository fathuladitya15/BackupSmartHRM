<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogCuti extends Model
{
    use HasFactory;

    protected $table  = 'table_log_cuti';

    protected $fillable = ['id_karyawan','total_hari','cuti_get','keterangan_cuti','tahun'];
}

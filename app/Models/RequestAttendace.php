<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestAttendace extends Model
{
    use HasFactory;

    protected $table = 'request_attendaces';
    protected $fillable = [
        'id_karyawan',
        'request_date',
        'request_time',
        'lokasi_absen',
        'detail_lokasi_absen',
        'approved_by',
        'approved_on',
        'shift',
        'catatan',
        'status',
        'latitude',
        'longitude',
        'id_client',
        'divisi'
    ];
}

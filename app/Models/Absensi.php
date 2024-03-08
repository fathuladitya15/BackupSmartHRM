<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'table_absensi';

    protected $fillable = [
     'id_karyawan',
     'nama_karyawan',
     'divisi',
     'jabatan',
     'tanggal',
     'jam_masuk',
     'lokasi_absen_masuk',
     'detail_lokasi_absen_masuk',
     'jam_keluar',
     'mulai_istirahat',
     'selesai_istirahat',
     'shift',
     'lokasi_absen_plg',
     'detail_lokasi_absen_plg',
     'latitude',
     'longitude',
     'catatan',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peringatan extends Model
{
    use HasFactory;

    protected $table = 'table_peringatan';

    protected $fillable = [
        'karyawan_id',
        'no_surat',
        'karyawan_id_pembuat',
        'tipe_peringatan',
        'alasan',
        'keterangan',
        'tanggal_pembuatan',
        'tanggal_berlaku',
        'tanggal_kadaluarsa',
        'nama_pemberi_sp',
        'status',
    ];
}

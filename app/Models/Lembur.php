<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    use HasFactory;

    protected $table = 'table_lembur';

    protected $fillable = [
       'id_karyawan',
       'nama_karyawan',
       'jabatan',
       'divisi',
       'lokasi_kerja',
       'tanggal_lembur',
       'batch',
       'group',
       'jam_mulai',
       'jam_selesai',

       'jam_mulai_rl',
       'jam_selesai_rl',

       'jam_mulai_la',
       'jam_selesai_la',

       'total_jam',
       'alasan_lembur',
       'tugas',
       'status',
       'ttd_admin_korlap',
       'ttd_direktur',
       'ttd_manajer',
       'ttd_karyawan',
       'disetujui_oleh',
       'id_client',
       'id_shift',
       'id_filemanager'
    ];
}

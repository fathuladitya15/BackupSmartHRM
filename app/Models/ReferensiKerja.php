<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferensiKerja extends Model
{
    use HasFactory;

    protected $table = 'table_referensi_kerja';

    protected $fillable = [
        'no_arsip',
        'no_surat',
        'periode',
        'tahun',
        'nama_karyawan',
        'id_karyawan',
        'id_client',
        'nik',
        'jabatan',
        'alamat',
        'awal_masuk',
        'akhir_masuk',
        'tanggal_pembuatan',
        'keterangan',
        'teks_keterangan',
        'ttd_pembuat',
        'nama_pembuat',
        'acc_surat',
        'ttd_direktur',
        'nama_direktur',
        'status',
    ];
}

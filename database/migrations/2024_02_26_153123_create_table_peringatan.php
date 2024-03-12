<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('table_peringatan', function (Blueprint $table) {
            $table->id();
            $table->string('karyawan_id');
            $table->string('nama_karyawan');
            $table->string('divisi');
            $table->string('jabatan');
            $table->string('lokasi_kerja');
            $table->string('no_surat');
            $table->string('karyawan_id_pembuat');
            $table->string('tipe_peringatan');
            $table->string('alasan');
            $table->longText('keterangan');
            $table->string('tanggal_pembuatan');
            $table->string('tanggal_berlaku');
            $table->string('tanggal_kadaluarsa');
            $table->string('nama_pemberi_sp');
            $table->string('status');
            $table->string('disetujui_pada')->nullable();
            $table->string('disetujui_oleh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_peringatan');
    }
};

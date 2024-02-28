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
        Schema::create('table_izin', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat')->nullable();
            $table->string('karyawan_id');
            $table->string('nama_karyawan');
            $table->string('divisi');
            $table->string('jabatan');
            $table->string('alasan')->nullable();
            $table->string('detail')->nullable();
            $table->string('tanggal_pembuatan')->nullable();
            $table->string('jam_masuk')->nullable();
            $table->string('jam_keluar')->nullable();
            $table->string('ttd_karyawan')->nullable();
            $table->string('ttd_mengetahui')->nullable();
            $table->string('ttd_hrd')->nullable();
            $table->string('ttd_direktur')->nullable();
            $table->string('user_id_mengetahui')->nullable();
            $table->string('disetujui_pada')->nullable();
            $table->string('disetujui_oleh')->nullable();
            $table->string('kembali')->nullable();
            $table->string('status')->nullable();
            $table->string('id_filemanager')->nullable();
            $table->string('id_client')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_izin');
    }
};

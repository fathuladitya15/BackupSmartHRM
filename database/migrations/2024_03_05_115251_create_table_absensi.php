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
        Schema::create('table_absensi', function (Blueprint $table) {
            $table->id();
            $table->string('id_karyawan');
            $table->string('nama_karyawan');
            $table->string('divisi');
            $table->string('jabatan');
            $table->date('tanggal');
            $table->string('jam_masuk');
            $table->string('lokasi_absen_masuk');
            $table->text('detail_lokasi_absen_masuk');

            $table->string('jam_keluar');
            $table->string('mulai_istirahat')->nullable();
            $table->string('selesai_istirahat')->nullable();
            $table->string('shift');
            $table->string('lokasi_absen_plg');
            $table->text('detail_lokasi_absen_plg');
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->text('catatan')->nullable();
            $table->string('id_client')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_absensi');
    }
};

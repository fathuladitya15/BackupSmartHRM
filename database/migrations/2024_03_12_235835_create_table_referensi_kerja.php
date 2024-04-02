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
        Schema::create('table_referensi_kerja', function (Blueprint $table) {
            $table->id();
            $table->string('no_arsip')->nullable();
            $table->string('no_surat')->nullable();
            $table->string('periode');
            $table->string('tahun');
            $table->string('nama_karyawan');
            $table->string('id_karyawan');
            $table->string('id_client');
            $table->string('nik');
            $table->string('jabatan');
            $table->string('alamat');
            $table->string('awal_masuk');
            $table->string('akhir_masuk');
            $table->date('tanggal_pembuatan');
            $table->string('keterangan');
            $table->text('teks_keterangan');
            $table->string('ttd_pembuat');
            $table->string('nama_pembuat');
            $table->string('acc_surat')->nullable();
            $table->string('acc_on')->nullable();
            $table->string('ttd_direktur')->nullable();
            $table->string('nama_direktur')->nullable();
            $table->string('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_referensi_kerja');
    }
};

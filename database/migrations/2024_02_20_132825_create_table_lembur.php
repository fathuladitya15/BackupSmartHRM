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
        Schema::create('table_lembur', function (Blueprint $table) {
            $table->id();
            $table->string('id_karyawan');
            $table->string('nama_karyawan');
            $table->string('jabatan');
            $table->string('divisi');
            $table->string('lokasi_kerja');
            $table->string('tanggal_lembur');
            $table->string('batch')->nullable();
            $table->string('group')->nullable();
            $table->string('jam_mulai')->nullable();
            $table->string('jam_selesai')->nullable();

            $table->string('jam_mulai_rl')->nullable();
            $table->string('jam_selesai_rl')->nullable();

            $table->string('jam_mulai_la')->nullable();
            $table->string('jam_selesai_la')->nullable();

            $table->string('total_jam')->nullable();
            $table->string('alasan_lembur')->nullable();
            $table->string('tugas')->nullable();
            $table->string('status');
            $table->string('ttd_admin_korlap')->nullable();
            $table->string('ttd_direktur')->nullable();
            $table->string('ttd_manager')->nullable();
            $table->string('ttd_karyawan')->nullable();
            $table->string('disetujui_oleh')->nullable();
            $table->string('id_client')->nullable();
            $table->string('id_shift')->nullable();
            $table->string('id_filemanager')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_lembur');
    }
};

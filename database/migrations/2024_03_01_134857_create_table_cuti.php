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
        Schema::create('table_cuti', function (Blueprint $table) {
            $table->id();
            $table->string('id_karyawan');
            $table->string('nama_karyawan');
            $table->string('divisi');
            $table->string('jabatan');
            $table->string('alasan');
            $table->string('kategori_cuti');
            $table->string('jumlah_cuti');
            $table->string('ambil_cuti');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status');
            $table->string('ttd_manager')->nullable();
            $table->string('ttd_hrd')->nullable();
            $table->string('ttd_direktur')->nullable();
            $table->string('disetujui_oleh')->nullable();
            $table->string('disetujui_pada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_cuti');
    }
};

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
            $table->string('karyawan_id');
            $table->string('keterangan_cuti');
            $table->string('jumlah_cuti');
            $table->string('ambil_cuti');
            $table->string('ttd_manager')->nullable();
            $table->string('ttd_hrd')->nullable();
            $table->string('ttd_direktur')->nullable();
            $table->string('disetujui_oleh')->nullable();
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

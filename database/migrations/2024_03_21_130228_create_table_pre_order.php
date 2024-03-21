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
        Schema::create('table_pre_order', function (Blueprint $table) {
            $table->id();
            $table->string('bulan');
            $table->string('no_po');
            $table->date('tanggal');
            $table->string('divisi');
            $table->date('batas_waktu');
            $table->string('dibuat_oleh');
            $table->string('ttd_pembuat');
            $table->string('disetujui_oleh')->nullable();
            $table->string('ttd_direktur')->nullable();
            $table->string('nama_direktur')->nullable();
            $table->string('status')->default(0);
            $table->string('id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_pre_order');
    }
};

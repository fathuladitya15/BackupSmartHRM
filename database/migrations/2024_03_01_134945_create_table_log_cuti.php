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
        Schema::create('table_log_cuti', function (Blueprint $table) {
            $table->id();
            $table->string('id_karyawan');
            $table->string('total_hari');
            $table->string('cuti_get');
            $table->string('keterangan_cuti');
            $table->string("tahun");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_log_cuti');
    }
};

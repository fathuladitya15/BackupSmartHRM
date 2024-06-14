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
        Schema::create('request_attendaces', function (Blueprint $table) {
            $table->id();
            $table->string('id_karyawan');
            $table->string('request_date');
            $table->string('request_time');
            $table->text('lokasi_absen');
            $table->text('detail_lokasi_absen');
            $table->string('approved_by')->nullable();
            $table->string('approved_on')->nullable();
            $table->string('shift');
            $table->string('catatan');
            $table->string('id_client');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('status')->default(0);
            $table->string('divisi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_attendaces');
    }
};

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
        Schema::create('table_lap_produksi_to_spv', function (Blueprint $table) {
            $table->id();
            $table->string('id_table_lap_period');
            $table->string('total_tagihan');
            $table->string('persentase');
            $table->string('hasil_persentase');
            $table->string('status');
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
        Schema::dropIfExists('table_lap_produksi_to_spv');
    }
};

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
        Schema::create('table_lap_period', function (Blueprint $table) {
            $table->id();
            $table->string('keterangan');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('total_produk')->nullable();
            $table->string('total_tagihan')->nullable();
            $table->string('id_client');
            $table->string('status')->default(0);
            $table->string('persentase')->nullable();
            $table->string('hasil_persentase')->nullable();
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
        Schema::dropIfExists('table_lap_period');
    }
};

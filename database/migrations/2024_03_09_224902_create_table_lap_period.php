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
            $table->string('total_produk');
            $table->string('total_tagihan');
            $table->string('id_client');
            $table->string('status');
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

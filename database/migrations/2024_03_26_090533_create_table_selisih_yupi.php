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
        Schema::create('table_selisih_yupi', function (Blueprint $table) {
            $table->id();
            $table->string('id_table_lap_produksi');
            $table->string('tanggal');
            $table->string('TotalCountProduk_DB');
            $table->string('TotalCountProduk_M')->default(0);
            $table->string('status')->default(0);
            $table->string('selisih')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_selisih_yupi');
    }
};

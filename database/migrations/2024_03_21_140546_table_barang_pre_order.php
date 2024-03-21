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
        Schema::create('table_barang_po', function (Blueprint $table) {
            $table->id();
            $table->string('id_pre_order');
            $table->string('nama_barang');
            $table->string('unit');
            $table->string('satuan');
            $table->string('biaya');
            $table->string('jumlah');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_barang_po');
    }
};

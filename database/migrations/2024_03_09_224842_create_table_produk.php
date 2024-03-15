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
        Schema::create('table_produk', function (Blueprint $table) {
            $table->id();
            $table->string('no_produk')->nullable();
            $table->string('tipe_produk');
            $table->string('nama_produk');
            $table->string('satuan_produk');
            $table->string('harga_produk');
            $table->string('id_client');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_produk');
    }
};

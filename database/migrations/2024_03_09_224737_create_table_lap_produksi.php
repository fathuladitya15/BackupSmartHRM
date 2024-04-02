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
        Schema::create('table_lap_produksi', function (Blueprint $table) {
            $table->id();
            $table->string('no_produk')->nullable();
            $table->string('tipe_produk');
            $table->string('nama_produk');
            $table->string('id_table_lap_period');
            $table->string('satuan_produk');
            $table->string('tanggal_1')->nullable();
            $table->string('tanggal_2')->nullable();
            $table->string('tanggal_3')->nullable();
            $table->string('tanggal_4')->nullable();
            $table->string('tanggal_5')->nullable();
            $table->string('tanggal_6')->nullable();
            $table->string('tanggal_7')->nullable();
            $table->string('tanggal_8')->nullable();
            $table->string('tanggal_9')->nullable();
            $table->string('tanggal_10')->nullable();
            $table->string('tanggal_11')->nullable();
            $table->string('tanggal_12')->nullable();
            $table->string('tanggal_13')->nullable();
            $table->string('tanggal_14')->nullable();
            $table->string('tanggal_15')->nullable();
            $table->string('tanggal_16')->nullable();
            $table->string('tanggal_17')->nullable();
            $table->string('tanggal_18')->nullable();
            $table->string('tanggal_19')->nullable();
            $table->string('tanggal_20')->nullable();
            $table->string('tanggal_21')->nullable();
            $table->string('tanggal_22')->nullable();
            $table->string('tanggal_23')->nullable();
            $table->string('tanggal_24')->nullable();
            $table->string('tanggal_25')->nullable();
            $table->string('tanggal_26')->nullable();
            $table->string('tanggal_27')->nullable();
            $table->string('tanggal_28')->nullable();
            $table->string('tanggal_29')->nullable();
            $table->string('tanggal_30')->nullable();
            $table->string('tanggal_31')->nullable();
            $table->string('total_produk')->nullable();
            $table->string('harga_produk_satuan')->nullable();
            $table->string('total_harga_produk')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_lap_produksi');
    }
};

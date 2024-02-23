<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('table_clients', function (Blueprint $table) {
            $table->id();
            $table->string('nama_client');
            $table->string('prov');
            $table->string('kota');
            $table->string('kec');
            $table->string('kel');
            $table->string('alamat_detail');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_clients');
    }
};

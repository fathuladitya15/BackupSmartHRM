<?php

use Auth;
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
        Schema::create('table_pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('pembuat');
            $table->text('keterangan');
            $table->string('views');
            $table->longText('lampiran')->nullable();
            $table->string('arsip')->default(0);
            $table->string('id_client')->default(Auth::user()->id_client);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_pengumuman');
    }
};

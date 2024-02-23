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
        Schema::create('table_karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_karyawan');
            $table->string('id_karyawan');
            $table->string('no_hp');
            $table->string('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->string('jenis_kelamin');
            $table->string('nik');
            $table->string('no_kk');
            $table->string('alamat');
            $table->string('alamat_domisili');
            $table->string('lokasi_kerja');
            $table->string('divisi');
            $table->string('jabatan');
            $table->string('join_date');
            $table->string('end_date');
            $table->string('no_sio')->nullable();
            $table->string('gol_karyawan');
            $table->string('marital');
            $table->string('tJabatan')->nullable();
            $table->string('tTransport')->nullable();
            $table->string('nama_bank');
            $table->string('cabang_bank');
            $table->string('kd_bank');
            $table->string('norek_bank');
            $table->string('status');
            $table->string('status_karyawan')->nullable();
            $table->string('disetujui_oleh')->nullable();
            $table->string('disetujui_pada')->nullable();
            $table->string('cuti')->nullable();
            $table->string('usia')->nullable();
            $table->string('nama_s_i')->nullable();
            $table->string('jmlh_anak')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nama_anak')->nullable();
            $table->string('nama_bapa')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('agama')->nullable();
            $table->string('kategori')->nullable();
            $table->string('pencairan_bpjstk')->nullable();
            $table->string('no_npwp')->nullable();
            $table->string('no_kpj')->nullable();
            $table->string('no_jkn')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_karyawan');
    }
};

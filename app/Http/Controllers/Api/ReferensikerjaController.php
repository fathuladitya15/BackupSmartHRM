<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangPreOrder;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\ReferensiKerja;
use App\Models\Clients;
use App\Models\User;
use App\Models\Filemanager;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ReferensikerjaController extends Controller
{
    function get_data(Request $request) {

        if($request->id_karyawan == "" || $request->id_karyawan == null){
            return response()->json(['pesan' => 'ID Karyawan dibutuhkan'],422);
        }

        $id_karyawan    = $request->id_karyawan;
        $cekID          = User::where('id_karyawan',$id_karyawan);

        if($cekID->count() == 0) {
            return response()->json(['pesan' => 'ID karyawan tidak ditemukan'],404);
        }

        $roles          = $cekID->first()->roles;

        if($roles != 'direktur') {
            return response()->json(['pesan' => 'Anda tidak memiliki akses'],401);
        }

        $divisi         = Karyawan::where('id_karyawan',$request->id_karyawan)->first()->divisi()->first()->nama_divisi;

        if($divisi != 'MPO') {
            return response()->json(['pesan' => 'Anda tidak memiliki akses'],401);
        }

        $data       =   ReferensiKerja::where('status','>=',2)->orderBy('created_at','DESC')->get();

        $result     =   [];

        foreach ($data as $key ) {
            $result[] = [
                'id'                => $key['id'],
                'nomor_surat'       => $key['no_surat'],
                'periode'           => $key['periode'],
                'nama_karyawan'     => $key['nama_karyawan'],
                'id_karyawan'       => $key['id_karyawan'],
                'created_at'        => $key['created_at'],
                'acc'               => $key['status'] >= 4 ? 1 : 0,
                'link'              => $key['status'] >= 4 ? route('dokumen-rf',['id' => $key['id']]) : "",
                'keterangan'        => $this->get_keterangan($key['keterangan']),
                'info'              => $this->info_status($key['status']),
                'created_at'        => $key['created_at'],
                'detail'            => $this->detail($key['id']),

            ];
        }

        return response()->json(['data' => $result]);
    }

    function detail ($id) {
        $data = ReferensiKerja::find($id);
        $result = [
            'nomor_surat'       => $data->no_surat,
            'periode'           => $data->periode,
            'tahun'             => $data->tahun,
            'nama_karyawan'     => $data->nama_karyawan,
            'id_karyawan'       => $data->id_karyawan,
            'nik'               => $data->nik,
            'jabatan'           => $data->jabatan,
            'alamat'            => $data->alamat,
            'join_date'         => Carbon::parse($data->awal_masuk)->translatedFormat('d F Y'),
            'end_date'          => Carbon::parse($data->akhir_masuk)->translatedFormat('d F Y'),
            'tanggal_pembuatan' => Carbon::parse($data->tanggal_pembuatan)->translatedFormat('d F Y'),
            'ttd_pembuat'       => asset($data->ttd_pembuat),
            'nama_pembuat'      => $data->nama_pembuat,
            'disetujui_oleh'    => $data->acc_surat,
            'disetujui_pata'    => $data->acc_on,
            'ttd_direktur'      => asset($data->ttd_direktur),
            'nama_direktur'     => $data->nama_direktur,
            'keterangan'        => $this->get_keterangan($data->keterangan),
            'keterangan_teks'   => $this->CleanString($data->teks_keterangan),
        ];

        return $result;

    }

    function get_keterangan($ket) {
        if($ket == 1) {
            $k = 'Mengundurkan diri';
        }else if($ket == 2) {
            $k = 'Habis Kontrak';
        }else if($ket == 3) {
            $k = 'Berkelakuan buruk';
        }else {
            $k = "";
        }
        return $k;
    }

    function CleanString($string){
        // Menghapus tag <br> dan karakter \n
        $cleanString = Str::replace(['<br>', '\n','\n\n','</br>',' \n'], ' ', $string);

        return $cleanString;
    }

    function info_status($status) {
        if($status == 2) {
            $s = 'Menunggu disetujui';
        }
        else if($status == 3){
            $s = 'Menuggu ditandatangani';
        }
        else {
            $s = "";
        }

        return $s;
    }

    function update_status(Request $request) {
        $id_karyawan = $request->id_karyawan;

        // Validasi apabila id karyawan kosong
        if($id_karyawan == "" || $id_karyawan == null) {
            return response()->json(['pesan' => "ID Karyawan dibutukan"],422);
        }

        $id = $request->id;
       
        $cekID = User::where('id_karyawan',$id_karyawan);

        // Validasi ketersediaa id karyawan
        if($cekID->count() == 0) {
            return response()->json(['pesan' => 'ID Karyawan tidak ditemukan'],404);
        }

        // ambil roles
        $roles = $cekID->first()->roles;


        // validasi roles harus direktur
        if($roles != 'direktur') {
            return response()->json(['pesan' => 'Anda tidak memiliki akses'],401);
        }

        // ambil divisi
        $divisi = Karyawan::where('id_karyawan',$id_karyawan)->first()->divisi()->first()->nama_divisi;


        // divisi harus MPO
        if($divisi != 'MPO') {
            return response()->json(['pesan' => 'Anda tidak memiliki akses'],401);
        }

        // Ambil Data
        $data = ReferensiKerja::find($id);

        // Validasi jika data tidak ada
        if(!$data) {
            return response()->json(['pesan' => 'Data tidak ditemukan'],404);
        }


        // Validasi untuk update harus ada ttd
        if($request->ttd == "" || $request->ttd == null) {
            return response()->json(['pesan' => 'Belum ditandatangani'],422);
        }

        // Pengecekan apabila data sudah di tanda tangani 
        if($data->status >= 4) {
            return response()->json(['pesan' => 'Anda telah menyetujui referensi kerja ini'],422);
        }

        if($request->ttd ==  1) {
            $ttdPath =  Filemanager::where('id_karyawan',$id_karyawan)->where('slug','signature')->first()->path;
            $data->ttd_direktur  = $ttdPath;
            $data->nama_direktur = $cekID->first()->name;
            $data->status        = 4;
            $data->update();

            return response()->json(['pesan' => 'Referensi kerja berhasil disetujui dan ditanda tangani']);

        }

        return response()->json(['pesan' => 'Terjadi kesalahan'],422);
    }

}

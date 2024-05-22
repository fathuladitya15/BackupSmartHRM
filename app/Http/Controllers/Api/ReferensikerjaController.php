<?php

namespace App\Http\Controllers\Api;

use Validator;
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
            'disetujui_pada'    => $data->acc_on,
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

    function createReferensiKerja(Request $request) {
        // return response()->json(['data' => $request->all()]);
        $id_karyawan = $request->id_karyawan_logged;

        if($id_karyawan  == null || $id_karyawan  == "") {
            return response()->json(['pesan' => "ID Karyawan dibutuhkan."],422);
        }

        $cekID = User::where('id_karyawan',$id_karyawan);

        if($cekID->count() == 0){
            return response()->json(['pesan' => "ID Karyawan tidak terdaftar."],404);
        }

        $dataUser = $cekID->first();

        if(!in_array($dataUser->roles,['admin','korlap'])) {
            return response()->json(['pesan' => 'Anda tidak memiliki akses.'],401);
        }

        $idClient = $dataUser->id_client;

        $getDataKaryawan = Karyawan::where('id_karyawan',$request->id_karyawan)->first();

        $rules = [
            'no_arsip'      => 'required',
            'periode'       => 'required',
            'tahun'         => 'required',
            'id_karyawan'   => 'required',
            'awal_masuk'    => 'required',
            'akhir_masuk'   => 'required',
            'tanggal_pembuatan' => 'required',
            'keterangan'    => 'required',
        ];

        $message = [
            'no_arsip.required'         => 'Nomor arsip wajib diisi.',
            'periode.required'          => 'Periode wajib diisi.',
            'tahun.required'            => 'Tahun wajib diisi.',
            'awal_masuk.required'       => 'Awal masuk wajib diisi.',
            'akhir_masuk.required'      => 'Akhir masuk wajib diisi.',
            'tanggal_pembuatan.required'=> 'Tanggal pembuatan wajib diisi.',
            'keterangan.required'       => 'Keterangan wajib diisi.',
        ];
        $validator = Validator::make($request->all(),$rules,$message);

        if($validator->fails()) {
            return response()->json(['pesan' => $validator->errors()->first()],500);
        }

        if($request->ttd != 1) {
            return response()->json(['pesan' => 'Dokumen belum ditanda tangani.'],422);
        }

        $text = $this->creteTeksKeterangan($request->keterangan,$request->id_karyawan,$request->awal_masuk,$request->akhir_masuk,$idClient);
        $ttd  = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','signature')->first()->path;
        $save = [
            'periode'           => $request->periode,
            'tahun'             => $request->tahun,
            'nama_karyawan'     => $getDataKaryawan->nama_karyawan,
            'id_karyawan'       => $request->id_karyawan,
            'id_client'         => $idClient,
            'nik'               => $getDataKaryawan->nik,
            'jabatan'           => $getDataKaryawan->jabatan,
            'alamat'            => $getDataKaryawan->alamat,
            'awal_masuk'        => $request->awal_masuk,
            'akhir_masuk'       => $request->akhir_masuk,
            'tanggal_pembuatan' => $request->tanggal_pembuatan,
            'keterangan'        => $request->keterangan,
            'teks_keterangan'   => $text,
            'ttd_pembuat'       => $ttd,
            'nama_pembuat'      => $dataUser->name,
            'no_arsip'          => $request->no_arsip,

        ];
        ReferensiKerja::create($save);
        return response()->json(['pesan' => 'Referensi kerja berhasil dibuat']);
    }

    function getReferensiKerjaAdmin(Request $request) {
        $id_karyawan = $request->id_karyawan;

        if($id_karyawan  == null || $id_karyawan  == "") {
            return response()->json(['pesan' => "ID Karyawan dibutuhkan."],422);
        }

        $cekID = User::where('id_karyawan',$id_karyawan);

        if($cekID->count() == 0){
            return response()->json(['pesan' => "ID Karyawan tidak terdaftar."],404);
        }

        $dataUser = $cekID->first();

        if(!in_array($dataUser->roles,['admin','korlap'])) {
            return response()->json(['pesan' => 'Anda tidak memiliki akses.'],401);
        }

        $idClient = $dataUser->id_client;

        $data = ReferensiKerja::where('id_client', $idClient)->get();

        $result     =   [];

        foreach ($data as $key ) {
            $result[] = [
                'id'                => $key['id'],
                'nomor_surat'       => $key['no_surat'],
                'periode'           => $key['periode'],
                'nama_karyawan'     => $key['nama_karyawan'],
                'id_karyawan'       => $key['id_karyawan'],
                'created_at'        => $key['created_at'],
                'link'              => $key['status'] >= 4 ? route('dokumen-rf',['id' => $key['id']]) : "",
                'keterangan'        => $this->get_keterangan($key['keterangan']),
                'info'              => $this->infoStatusAdmin($key['status']),
                'created_at'        => $key['created_at'],
                'detail'            => $this->detailAdmin($key['id']),

            ];
        }

        return response()->json(['data' => $result]);
    }

    function infoStatusAdmin($status) {
        if(in_array($status, [0,1])) {
            $s = 'Menunggu disetujui Supervisor';
        }else if(in_array($status, [2,3])) {
            $s = 'Menunggu ditandatangani Direktur';
        }else if($status == 4) {
            $s = 'Sedang diriview Supervisor';
        }else if($status == 5) {
            $s = 'Terkirim ke karyawan';
        }else {
            $s = '';
        }
        return $s;
    }

    function detailAdmin($id) {
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
            'disetujui_pada'    => $data->acc_on,
            'ttd_direktur'      => $data->nama_direktur == null ? "" : asset($data->ttd_direktur),
            'nama_direktur'     => $data->nama_direktur == null ? "" : $data->nama_direktur,
            'keterangan'        => $this->get_keterangan($data->keterangan),
            'keterangan_teks'   => $this->CleanString($data->teks_keterangan),
        ];

        return $result;
    }

    function creteTeksKeterangan($id_ket,$id_karyawan,$d_awal_masuk,$a_akhir_masuk,$id_client) {
        $nama_karyawan  = Karyawan::where('id_karyawan',$id_karyawan)->first()->nama_karyawan;
        $awal_masuk     = Carbon::parse($d_awal_masuk)->translatedFormat('d F Y');
        $akhir_masuk    = Carbon::parse($a_akhir_masuk)->translatedFormat('d F Y');
        $nama_client    = Clients::find($id_client)->nama_client;

        if($id_ket == 1) {
            $text = 'Adalah benar bahwa nama tersebut diatas  bekerja di PT Proven Force Indonesia yang di tempatkan di '.$nama_client.' terhitung mulai '.$awal_masuk.' sampai dengan '.$akhir_masuk.'. Dalam hal ini yang bersangkutan telah mengundurkan diri. <br>
            Adapun selama bekerja bersama kami, Sdr/i. '.$nama_karyawan.' telah menunjukan sikap yang baik, untuk itu kami atas nama perusahaan menyampaikan terimakasih atas segala sumbangsih yang telah diberikan dan semoga dimasa yang akan datang menjadi lebih sukses dan berhasil. </br>
            Demikian surat referensi kerja ini dibuat untuk dapat dipergunakan sebagaimana mestinya.';
        }else if($id_ket == 2) {
            $text = 'Adalah benar bahwa nama tersebut diatas  bekerja di PT Proven Force Indonesia yang di tempatkan di '.$nama_client.' mulai tanggal '.$awal_masuk.' sampai dengan '.$akhir_masuk.'. Dalam hal ini yang bersangkutan telah habis masa kontraknya. <br>
            Adapun selama bekerja bersama kami, Sdra/i. '.$nama_karyawan.' telah menunjukan sikap yang baik, untuk itu kami atas nama perusahaan menyampaikan terimakasih atas segala sumbangsih yang telah diberikan dan semoga dimasa yang akan datang menjadi lebih sukses dan berhasil. </br>
            Demikian surat referensi kerja ini dibuat untuk dapat dipergunakan sebagaimana mestinya.';
        }elseif($id_ket == 3) {
            $text  = 'Adalah benar bahwa nama tersebut diatas  bekerja di PT Proven Force Indonesia yang di tempatkan di '.$nama_client.' terhitung mulai tanggal '.$awal_masuk.' sampai dengan '.$akhir_masuk.'. Dalam hal ini yang bersangkutan telah mengundurkan diri.<br>

            Demikian surat referensi kerja ini dibuat untuk dapat dipergunakan sebagaimana mestinya.';
        }

        return $text;
    }

}

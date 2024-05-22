<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peringatan;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Filemanager;
use App\Models\Clients;
// use App\Models\Filemanager;
use Validator;
use DB;
use Carbon\Carbon;

class PeringatanController extends Controller
{

    function createPeringatanAdmin(Request $request) {
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

        $rules = [
            'id_karyawan'       => 'required',
            'no_sp'             => 'required',
            'tanggal_berlaku'   => 'required',
            'tanggal_kadaluarsa'=> 'required',
            'peringatan_ke'     => 'required',
            'nama_pemberi'      => 'required',
            'alasan_peringatan' => 'required',
        ];

        $message = [
            'id_karyawan.required'          => 'Harap pilih karyawan yang akan diberikan peringatan.',
            'no_sp.required'                => 'Nomor surat wajib diisi.',
            'tanggal_berlaku.required'      => 'Tanggal berlaku surat wajib diisi.',
            'tanggal_kadaluarsa.required'   => 'Tanggal kadaluarsa surat wajib diisi',
            'peringatan_ke.required'        => 'Tipe peringatan wajib diisi.',
            'nama_pemberi.required'         => 'Nama pemberi surat peringatan wajib diisi.',
            'alasan_peringatan.required'    => 'Alasan peringatan wajib diisi.'
        ];

        $validasi = Validator::make($request->all(),$rules,$message);

        if($validasi->fails()) {
            return response()->json(['pesan' => $validasi->errors()->first()],422);
        }

        if($request->ttd == "" || $request->ttd == null) {
            return response()->json(['pesan' => 'Dokumen belum ditandatangani'],422);
        }

        $cekIDKaryawanCreate = User::where('id_karyawan',$request->id_karyawan)->count();

        if($cekIDKaryawanCreate == 0) {
            return response()->json(['pesan' => 'ID Karyawan tidak ditemukan.'],404);
        }

        $ms                 = 90;
        $masa_berlaku       = Carbon::parse($request->tanggal_berlaku);
        $sampai_dengan      = Carbon::parse($request->tanggal_kadaluarsa);
        $cek_masa_berlaku   = $masa_berlaku->diffInDays($sampai_dengan);
        $tanggal_ms         = Carbon::parse($masa_berlaku->addDays($ms))->translatedFormat('d F Y') ;

        if($cek_masa_berlaku < $ms  || $cek_masa_berlaku > $ms){
            return response()->json(['pesan' =>'Masa berlaku surat harus '.$tanggal_ms.'' ],422);
        }

        $dataKaryawan       = Karyawan::where('id_karyawan',$request->id_karyawan)->first();
        $keterangan         = "Peringatan ini berlaku selama 3 bulan, ".Carbon::parse($request->mulai_dari)->translatedFormat("d F Y")." s/d ".$sampai_dengan->translatedFormat('d F Y')." . Apabila Mengulangi Pelanggaran Yang Sama Dalam Kurun Waktu Sama Berlakunya Sanksi Disiplin, Akan Dikenakan Sanksi Lebih Tinggi Dari Sebelumnya (Sesuai Peraturan PT Proven Force Indonseia)";

        $dataInput  = [
            'no_surat'              => $request->no_sp,
            'karyawan_id'           => $request->id_karyawan,
            'nama_karyawan'         => $dataKaryawan->nama_karyawan,
            'jabatan'               => $dataKaryawan->jabatan()->first()->nama_jabatan,
            'divisi'                => $dataKaryawan->divisi()->first()->nama_divisi,
            'lokasi_kerja'          => $dataKaryawan->lokasi_kerja,
            'karyawan_id_pembuat'   => $id_karyawan,
            'tipe_peringatan'       => $request->peringatan_ke,
            'alasan'                => $request->alasan_peringatan,
            'keterangan'            => $keterangan,
            'tanggal_pembuatan'     => Carbon::now()->format('d-m-Y'),
            'tanggal_berlaku'       => $request->tanggal_berlaku,
            'tanggal_kadaluarsa'    => $request->tanggal_kadaluarsa,
            'nama_pemberi_sp'       => $request->nama_pemberi,
            'status'                => 1,
        ];
        Peringatan::create($dataInput);

        return response()->json(['pesan' => 'Surat peringatan berhasil dibuat']);
    }

    function getPeringatanAdmin(Request $request) {
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

        $data = Peringatan::where('lokasi_kerja',$idClient)->get();

        $result = [];
        foreach($data as $key) {
            if($key->tipe_peringatan == 1) {
                $p = 'Pertama';
            }else if($key->tipe_peringatan == 2) {
                $p = 'Kedua';
            }else if($key->tipe_peringatan == 3) {
                $p = 'Ketiga';
            }else {
                $p = '';
            }
            $result[] = [
                'id'            => $key->id,
                'no_surat'      => $key->no_surat,
                'id_karyawan'   => $key->karyawan_id,
                'nama_karyawan' => $key->nama_karyawan,
                'divisi'        => $key->divisi,
                'jabatan'       => $key->jabatan,
                'lokasi_kerja'  => Clients::find($key->lokasi_kerja)->nama_client,
                'info'          => $this->statusAdmin($key->status),
                'peringatan'    => $p,
                'detail'        => [
                    'id_karyawan'       => $key->karyawan_id,
                    'nama_karyawan'     => $key->nama_karyawan,
                    'tanggal_berlaku'   => Carbon::parse($key->tanggal_berlaku)->translatedFormat('d F Y'),
                    'tanggal_kadaluarsa'   => Carbon::parse($key->tanggal_kadaluarsa)->translatedFormat('d F Y'),
                    'pemberi_sp'        => $key->nama_pemberi_sp,
                    'keterangan'        => $key->keterangan,
                    'link'              => $key->status != 3 ?  '' : route('peringatan-file',['id' => $key->id]),

                ]
            ];
        }

        return response()->json(['data' => $result]);
    }

    function statusAdmin($status) {
        if($status == 0) {
            $info = ' Sedang diriview ';
        }else  if($status == 1) {
            $info = ' Sedang diriview ';
        }else if($status == 2) {
            $info = ' Disetujui ';
        }else if($status == 3){
            $info = ' Telah disampaikan ';
        }else {
            $info = '';
        }
        return $info;
    }
}

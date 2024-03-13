<?php

namespace App\Http\Controllers;

use PDF;
use Auth;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Clients;
use Carbon\Carbon;
use App\Models\Filemanager;
use App\Models\ReferensiKerja;
use Illuminate\Http\Request;

class ReferensiKerjaController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }

    function index() {
        $role = Auth::user()->roles;
        if(in_array($role,['admin','korlap'])){
            $karyawan   = User::where('id_client',Auth::user()->id_client)->where('roles','karyawan')->get();
            return view('layouts.admin_korlap.vReferensiKerja',compact('karyawan'));
        }else if($role == 'spv-internal'){
            return view('layouts.spv.vReferensiKerja');
        }else if($role == 'direktur') {
            return view('layouts.direktur.vReferensiKerja');
        }else if($role == 'karyawan') {
            return view('layouts.karyawan.vReferensiKerja');

        }else {
            abort(404);
        }
    }

    function  save(Request $request) {
        $id = $request->id_rf;

        if($request->ttd != null){
            if($id == null) {
                $text = $this->keterangan($request->keterangan,$request->id_karyawan,$request->awal_masuk,$request->akhir_masuk,Auth::user()->id_client);
                $nama_karyawan  = Karyawan::where('id_karyawan',$request->id_karyawan)->first()->nama_karyawan;
                $ttd            = Filemanager::where('id_karyawan',Auth::user()->id_karyawan)->where('slug','signature')->first()->path;
                $nama_client    = Clients::find(Auth::user()->id_client)->nama_client;
                $save = [
                    'periode'           => $request->periode,
                    'tahun'             => $request->tahun,
                    'nama_karyawan'     => $nama_karyawan,
                    'id_karyawan'       => $request->id_karyawan,
                    'id_client'         => Auth::user()->id_client,
                    'nik'               => $request->nik,
                    'jabatan'           => $request->jabatan,
                    'alamat'            => $request->alamat,
                    'awal_masuk'        => $request->awal_masuk,
                    'akhir_masuk'       => $request->akhir_masuk,
                    'tanggal_pembuatan' => $request->tanggal_pembuatan,
                    'keterangan'        => $request->keterangan,
                    'teks_keterangan'   => $text,
                    'ttd_pembuat'       => $ttd,
                    'nama_pembuat'      => Auth::user()->name,
                    'no_arsip'          => $request->no_arsip,

                ];
                ReferensiKerja::create($save);
                $pesan = ['status' => TRUE,'title' => 'Sukses','pesan' => 'Surat Referensi Kerja Berhasil dibuat !'];
            }else {
                $pesan = ['data' => $pesan];
            }
        }else {
            $pesan = ['status' => FALSE,'title' => 'Ups !','pesan' => 'Surat Belum ditanda tangani !'];
        }

        return response()->json($pesan);
    }

    function keterangan($id_ket,$id_karyawan,$d_awal_masuk,$a_akhir_masuk,$id_client) {
        $nama_karyawan  = Karyawan::where('id_karyawan',$id_karyawan)->first()->nama_karyawan;
        $awal_masuk     = Carbon::parse($d_awal_masuk)->translatedFormat('d F Y');
        $akhir_masuk    = Carbon::parse($a_akhir_masuk)->translatedFormat('d F Y');
        $nama_client    = Clients::find($id_client)->nama_client;

        $default        = 'Adalah benar bahwa nama tersebut diatas bekerja di PT Proven Force Indonesia yang di tempatkan '.$nama_client.' terhitung mulai tanggal '.$awal_masuk.' sampai dengan '.$akhir_masuk.'. Dalam hal ini telah';

        $end_default    = ' Demikian surat referensi kerja ini dibuat untuk dapat dipergunakan sebagaimana mestinya.';
        if($id_ket == 1) {
            $addDefault = $default.' Habis Masa Kontraknya';
            $text = ' Adapun selama bekerja bersama kami, Sdr/i. '.$nama_karyawan.' telah menunjukan sikap yang baik,untuk itu kami atas nama perusahaan menyampaikan terimakasih atas segala sumbangsih yang telah diberikan dan semoga dimasa yang akan datang menjadi lebih sukses dan berhasil.';
        }else if($id_ket == 2) {
            $addDefault = $default.' Mengundurkan diri.';
            $text = 'Adapun selama bekerja bersama kami, Sdr/i. '.$nama_karyawan.' telah menunjukan sikap yang baik,untuk itu kami atas nama perusahaan menyampaikan terimakasih atas segala sumbangsih yang telah diberikan dan semoga dimasa yang akan datang menjadi lebih sukses dan berhasil.';
        }else {
            $addDefault = 'Adalah benar bahwa nama tersebut diatas bekerja di PT Proven Force Indonesia yang di tempatkan '.$nama_client.' terhitung mulai tanggal '.$awal_masuk.' sampai dengan '.$akhir_masuk.'.';
            $text = '';
        }

        return $addDefault.$text.$end_default;
    }

    function update(Request $request) {
        $id     = $request->id;
        $status = $request->status;
        $latest_status = 2;


        $data = ReferensiKerja::find($id);

        if($data->status != $latest_status) {
            if(Auth::user()->roles == 'direktur') {
                $ttd         = Filemanager::where('id_karyawan',Auth::user()->id_karyawan)->where('slug','signature')->first()->path;
                if($request->ttd != null) {
                    $data->ttd_direktur = $ttd;
                    $data->status = $status;
                    $data->update();
                    $pesan = ['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Surat Berhasil ditandatangani!','data' => $request->all()];

                }else {
                    $pesan = ['status' => FALSE,'title' => 'Ups !','pesan' => 'Surat Belum ditanda tangani !'];
                }
            }else {
                $data->status = $status;
                if($status == 2) {
                    $data->acc_surat = Auth::user()->name;
                    $data->no_surat  = $request->no_surat;
                }
                $data->update();
                $pesan = ['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Surat Berhasil disetujui!','data' => $request->all()];
            }

        }else {
            $data->status = $status;
            $data->update();
            $pesan = ['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Surat Berhasil disetujui!','data' => $request->all()];

        }
        return response()->json($pesan);

    }

    function detail(Request $request) {
        $data = ReferensiKerja::find($request->id);

        return response()->json($data);
    }

    function dokumen($id){
        $data   = ReferensiKerja::find($id);
        $pdf    = PDF::loadview('layouts.pdf_view.pdfReferensiKerja',['data' => $data]);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream();
    }
}

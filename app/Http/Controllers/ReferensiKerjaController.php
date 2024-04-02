<?php

namespace App\Http\Controllers;

use PDF;
use Auth;
use DataTables;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Clients;
use App\Models\Karyawan;
use App\Models\Filemanager;
use Illuminate\Http\Request;
use App\Models\ReferensiKerja;

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

    function index_direktur($client, $hash) {
        // dd($client);
        return view('layouts.direktur.vReferensiKerja');

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
                    $data->acc_on    = Carbon::now()->translatedFormat("l,d F Y");
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
        // $text = $this->teks;
        return response()->json($data);
    }

    function dokumen($id){
        $data   = ReferensiKerja::find($id);
        $pdf    = PDF::loadview('layouts.pdf_view.pdfReferensiKerja',['data' => $data]);
        $pdf->setPaper('A4', 'potrait');
        return $pdf->stream();
    }

    function data_direktur(Request $request) {

        $data = ReferensiKerja::where('id_client',$request->id_client)->get();
        $dt = DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('tanggal_pembuatan', function($row) {
            return Carbon::parse($row->tanggal_pembuatan)->translatedFormat('d F Y');
        })
        ->addColumn('awal_masuk', function($row) {
            return Carbon::parse($row->awal_masuk)->translatedFormat('d F Y');
        })
        ->addColumn('akhir_masuk', function($row) {
            return Carbon::parse($row->akhir_masuk)->translatedFormat('d F Y');
        })
        ->addColumn('keterangan', function($row) {
            if($row->keterangan == 1) {
                $s = 'Menundurkan Diri';
            }else if($row->keterangan == 2) {
                $s = 'Habis Kontrak';
            }else if($row->keterangan == 3) {
                $s = 'Berkelakuan Buruk';
            }else {
                $s = "";
            }
            return $s;
        })

        ->addColumn('status',function ($row) {
            $role = Auth::user()->roles;
            if($role != 'karyawan') {
                if(in_array($row->status, [0,1])) {
                    $s = '<span class="badge badge bg-warning"> Menunggu ditandatangani Supervisor </span>';
                }else if(in_array($row->status, [2,3])) {
                    $s = '<span class="badge badge bg-warning"> Menunggu ditandatangani Direktur </span>';
                }else if($row->status == 4) {
                    $s = '<span class="badge badge bg-warning"> Sedang diriview Supervisor </span>';
                }else if($row->status == 5) {
                    $s = '<span class="badge badge bg-success"> Terkirim ke karyawan </span>';
                }else {
                    $s = '';
                }
            }else {
                $s = "";
            }
            return $s;
        })
        ->addColumn('aksi', function($row) {
            $detail   = '<a href="javascript:void(0)" class="btn btn-primary btn-sm" id="detail_'.$row->id.'" onclick="detail('.$row->id.')"  ><i class="bx bx-edit-alt"></i>Detail</a>';
            $acc      = '<a href="javascript:void(0)" class="btn btn-success btn-sm" id="acc_'.$row->id.'" onclick="acc('.$row->id.')"  ><i class="bx bx-check"></i>Setujui</a>';
            $dokumen  = '<a href="'.route('dokumen-rf',['id' => $row->id]).'" class="btn btn-danger btn-sm" ><i class=bx bx-file ></i> Lihat File</a>';
            $role = Auth::user()->roles;
            if($row->status == 2) {
                $btn = $acc;
            }else if($row->status == 3) {
                $btn = $detail;
            }else {
                $btn = "";
            }
            return $btn;
        })
        ->rawColumns(['aksi','status','keterangan','awal_masuk','akhir_masuk','tanggan_pembuatan'])
        ->make(true);
        return $dt;
    }

}

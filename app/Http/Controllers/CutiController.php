<?php

namespace App\Http\Controllers;

use DB;
USE PDF;
use Auth;
use Datetime;
use Carbon\Carbon;
use App\Models\Cuti;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\LogCuti;
use App\Models\Karyawan;
use App\Models\Filemanager;
use App\Models\KategoriCuti;
use Illuminate\Http\Request;

class CutiController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }

    function index() {
        $roles = Auth::user()->roles;

        if(in_array($roles,['kr-project','kr-pusat'])){
            $kategori_cuti = KategoriCuti::all();
            $data          = Karyawan::where("id_karyawan",Auth::user()->id_karyawan)->first();
            $divisi        = Divisi::find($data->divisi)->nama_divisi;
            $jabatan       = Jabatan::find($data->jabatan)->nama_jabatan;
            $periode_cuti  = 12;
            $tahun_ini     = Carbon::now()->format('Y');

            $total_semua_cuti = DB::table('table_log_cuti')->where('id_karyawan',Auth::user()->id_karyawan)
                                ->where('tahun',$tahun_ini)
                                ->selectRaw('SUM(CAST(cuti_get AS INT)) as cuti_yang_diambil')->first();
            $sisa_cuti     = 12 -  $total_semua_cuti->cuti_yang_diambil;
            // dd($sisa_cuti);
            return view('layouts.karyawan.vCuti',compact('kategori_cuti','divisi','jabatan','periode_cuti','sisa_cuti'));

        }
        else if($roles == 'manajer') {
            return view('layouts.manajer.vCuti');
        }
        else if($roles == 'hrd') {
            $kategori_cuti = KategoriCuti::all();
            $karyawan = DB::table("users")->whereIn('roles',['kr-project','admin','korlap','spv-internal','kr-pusat','manajer'])->get();
            $cuti     = Cuti::all()->count();
            $wait_acc = Cuti::where('status',1)->count();
            return view('layouts.hrd.vCuti',compact("karyawan",'kategori_cuti','wait_acc','cuti'));
        }
        else if($roles == 'direktur') {
            $cek_divisi   = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first();
            $divisi       = Divisi::find($cek_divisi->divisi)->nama_divisi;

            if($divisi == 'MPO'){
                return view('layouts.manajer.vCuti');
            }else {
                return view('layouts.direktur.vCuti');
            }

        }
    }

    function cuti_data(Request $request) {
        $data           = Karyawan::where('id_karyawan',$request->id)->first();
        $jabatan        = Jabatan::find($data->jabatan)->nama_jabatan;
        $divisi         = Divisi::find($data->divisi)->nama_divisi;
        $nama_karyawan  = $data->nama_karyawan;

        $tahun_ini      = Carbon::now()->format('Y');

        $get_log_cuti   = LogCuti::where("id_karyawan",$request->id)->where('tahun',$tahun_ini);
        if($get_log_cuti->count() > 0) {
            $sisa_cuti               = 12;
            $total_cuti_yang_diambil = DB::table('table_log_cuti')->selectRaw('SUM(convert(int,cuti_get)) as cuti_yang_diambil')->first()->cuti_yang_diambil ;
            $jmlh_cuti               = $sisa_cuti - $total_cuti_yang_diambil ;

        }else {
            $jmlh_cuti = 12;
            $sisa_cuti = 12;
            $p  = FALSE;
        }

        $data = [
            'jabatan' => $jabatan,
            'divisi'  => $divisi,
            'nama'    => $nama_karyawan,
            'tahun_ini' => $tahun_ini,
            'sisa_cuti' => $sisa_cuti,
            'total_cuti' => $jmlh_cuti,
            'nama_karyawan' => $nama_karyawan,
        ];

        return response()->json($data);
    }

    function detail_data_cuti(Request $request) {
        $data = Cuti::find($request->id);
        return response()->json(['status' => TRUE,'data' => $data]);
    }

    function validasi_tanggal_cuti(Request $request)  {

        $batas_hari     = $request->batas_hari;

        $start_date_new = Carbon::parse($request->start_date);
        $end_date_new   = Carbon::parse($request->end_date);

        // SABTU MINGGU TIDAK DI HITUNG
        $not_work_hours = $start_date_new->diffInDaysFiltered(function(Carbon $date) {
            return $date->isWeekend();
        }, $end_date_new);

        //PERHITUNGAN TANGGAL
        $all_days       = $start_date_new->diffInDays($end_date_new) + 1;
        // TOTAL CUTI
        $total_cuti = $all_days - $not_work_hours;

        if($request->end_date) {
            if($total_cuti > $batas_hari) {
                $status = ['status' => FALSE,'title' => 'Cuti Melebihi ','pesan' => 'Total hari yang anda masukan melebihi dari sisa cuti yang tersedia'];
            }else {

                $status = ['status' => TRUE ,'title' => 'Cuti tervalidasi','total_cuti' => $total_cuti] ;

            }
        }else {
            $status = ['masukan tanggal selesai cuti'];
        }
        return response()->json($status);

    }

    function save_cuti(Request $request) {

        $role = Auth::user()->roles;
        $ttd  = Filemanager::where("id_karyawan",Auth::user()->id_karyawan)->where("slug",'signature')->first();
        $id_jabatan  = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first()->jabatan;

        if($request->ttd == 1){
            if(in_array($role,['kr-project','kr-pusat','manajer'])) {

                $jabatan = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->with('jabatan')->first()->jabatan()->first()->nama_jabatan;
                // if($jabatan == 'Manager') {
                //     $status = 2;
                // }else {
                //     $stats = 0;
                // }


                $ttd  = Filemanager::where("id_karyawan",Auth::user()->id_karyawan)->where("slug",'signature')->first();
                $cutiCreate = [
                    'id_karyawan'   => $request->id_karyawan,
                    'nama_karyawan' => $request->nama_karyawan,
                    'divisi'        => $request->divisi,
                    'jabatan'       => $request->jabatan,
                    'kategori_cuti' => $request->kategori_cuti,
                    'alasan'        => $request->alasan_cuti,
                    'ambil_cuti'    => $request->total_cuti_hari,
                    'jumlah_cuti'   => $request->total_cuti,
                    'ttd_karyawan'  => $ttd->path,
                    'start_date'    => $request->start_date,
                    'end_date'      => $request->end_date,
                    'status'        => $jabatan == 'Manager' ? 2 : 0,
                ];

                Cuti::create($cutiCreate);

                $logCutiCreate = [
                    'id_karyawan' => $request->id_karyawan,
                    'total_hari'  => $request->total_cuti,
                    'cuti_get'    => $request->total_cuti_hari,
                    'keterangan_cuti' => $request->kategori_cuti,
                    'tahun'       => Carbon::parse($request->start_date)->format("Y")  ,
                ];
                LogCuti::create($logCutiCreate);

                $pesan = ['status' => TRUE,'title' => 'Sukses ', 'pesan' => 'Cuti dengan ID Karyawan : '.$request->id_karyawan.' berhasil ditambahkan'];

            }
            else if($role == 'direktur') {
                $data = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first();
                $divisi  = Divisi::find($data->divisi)->nama_divisi;
                if($divisi == 'MPO'){
                    $data = Cuti::find($request->id_cuti);
                    $data->status = 1;
                    $data->ttd_manager = $ttd->path;
                    $data->update();
                    $pesan = ['status' => TRUE,'title' => 'Berhasil ditandatangani' ,'pesan' => "ID Karyawan : ".$request->id_karyawan." berhasil ditandatangani"];
                }
                else{
                    $data = Cuti::find($request->id_cuti);
                    if($data->status == 2) {
                        $data->status = 3;
                        $data->ttd_direktur = $ttd->path;
                        $data->update();
                        $pesan = ['status' => TRUE,'title' => 'Berhasil ditandatangani' ,'pesan' => "ID Karyawan : ".$request->id_karyawan." berhasil ditandatangani"];

                    }else if($data->status == 3) {
                        $data->status = 4;
                        $data->update();
                        $pesan = ['status' => TRUE,'title' => 'Berhasil disetujui' ,'pesan' => "ID Karyawan : ".$request->id_karyawan." berhasil disetujui"];

                    }else {
                        $pesan = ['status' => FALSE,'data' => $data->status ];

                    }
                }
            }
            else if($role == 'hrd') {
                $data = Cuti::find($request->id_cuti);
                $data->status = 2;
                $data->ttd_hrd = $ttd->path;
                $data->update();
                $pesan = ['status' => TRUE,'title' => 'Berhasil ditandatangani' ,'pesan' => "ID Karyawan : ".$request->id_karyawan." berhasil ditandatangani"];
            }
            else {
                $pesan = ['status' => FALSE,'title' => 'Ops' ];

            }
        }else {
            $pesan = ['status' => FALSE,'title' => "Belum ditandatangani" ,'pesan' => "Tandatangani terlebih dahulu"];

        }

        return response()->json($pesan);
    }

    function kategori_cuti() {
        return view('layouts.hrd.vKategori_cuti');
    }

    function kategori_cuti_save(Request $request){
        if($request->id_cuti == null) {
            KategoriCuti::create([
                'nama_kategori' => $request->nama_kategori,
            ]);
            $pesan = ['status' => TRUE,'title' => 'sukses' ,'pesan' => 'Berhasil menambahkan kategori cuti'];
        }else {
            $find = KategoriCuti::find($request->id_cuti);
            $find->nama_kategori = $request->nama_kategori;
            $find->update();
            $pesan = ['status' => TRUE,'title' => 'sukses' , 'pesan' => 'Kategori Cuti berhasil diperbarui'];

        }

        return response()->json($pesan);
    }

    function kategori_cuti_get(Request $request) {

        $data = KategoriCuti::find($request->id);

        if($data) {
            $status = ['status' => TRUE,'data' => $data];
        }else{
            $status = ['status' => FALSE];
        }

        return response()->json($status);
    }

    function kategori_cuti_delete(Request $request) {
        $get = KategoriCuti::findOrFail($request->id);
        $get->delete();
        if($get) {
            $status = ['status' => TRUE ,'title' => 'sukses','pesan' => "Kategori berhasil di hapus"];
        }else{
            $status = ['status' => FALSE,'title' => 'Error' ,'pesan' => 'Hubungi Tim IT terkait'];
        }
        return response()->json($status);

    }

    function dokumen_cuti($id) {
        $data       = Cuti::find($id);
        $filename   = 'Form Cuti '.$data->nama_karyawan;
        $jabatan    = Karyawan::where('id_karyawan',$data->id_karyawan)->with('jabatan')->first()->jabatan()->first()->nama_jabatan;
        $pdf        = PDF::loadview("layouts.pdf_view.pdfFormCuti",['data' => $data,'filename'=> $filename,'jabatan' => $jabatan]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream($filename.'.pdf');
    }
}

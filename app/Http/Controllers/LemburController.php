<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use Auth;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Lembur;
use App\Models\Shift;
use App\Models\Divisi;
use App\Models\Clients;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Filemanager;
use Illuminate\Http\Request;

class LemburController extends Controller
{
    function __construct() {
        $this->middleware("auth");
    }

    function index() {


        if(in_array(Auth::user()->roles,['hrd','manajer','direktur'])) {
            $daftar_kr = User::where('roles','kr-pusat')->orWhere('roles','kr-project')->where('id_client',Auth::user()->id_client)->get();
            $total_jam = DB::table('table_lembur')
                ->selectRaw('SUM(CAST(total_jam as int)) as jam')
                ->where('id_client',Auth::user()->id_client)
                ->first()->jam;
            $kr_lembur = DB::table('table_lembur')->distinct()->where('id_client',Auth::user()->id_client)->count('id_karyawan');
            $wait_lembur = Lembur::where('id_client',Auth::user()->id_client)->where('status','0')->count();
            return view('layouts.hrd.vLembur',compact('daftar_kr'));
        }else if(in_array(Auth::user()->roles,['kr-pusat','kr-project'])) {
            $datakr         = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first();
            $jabatan        = Jabatan::find($datakr->jabatan)->nama_jabatan;
            $divisi         = Divisi::find($datakr->divisi)->nama_divisi;
            $lokasi_kerja   = Clients::find(Auth::user()->id_client)->nama_client;
            return view('layouts.lembur.vLemburPFI',compact('lokasi_kerja','divisi','jabatan'));

        }else if(Auth::user()->roles == 'karyawan'){
            $id_client      = Auth::user()->id_client;
            $data_karyawan  = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first();
            $lokasi_kerja   = Clients::find($data_karyawan->lokasi_kerja)->nama_client;
            $jabatan        = Jabatan::find($data_karyawan->jabatan)->nama_jabatan;
            $divisi         = Divisi::find($data_karyawan->divisi)->nama_divisi;
            $shift          = Shift::where('id_client',$id_client)->get();

            if($id_client == '2') {
                $view = 'layouts.lembur.vLemburDefault';
                // return view('layouts.lembur.vLemburDefault',compact('divisi','jabatan','lokasi_kerja'));
            }else {
                $view = 'layouts.lembur.vAIOSukabumi';
                // return view('layouts.lembur.vAIOSukabumi',compact("data_karyawan","jabatan","divisi",'lokasi_kerja'));
            }

            return view($view,compact('divisi','jabatan','lokasi_kerja','data_karyawan','shift'));


        }else if(in_array(Auth::user()->roles,['admin','korlap'])) {
            $daftar_kr  = DB::table('table_karyawan as tk')
                ->join('users as us','us.id_karyawan','=','tk.id_karyawan')
                ->where('us.roles','karyawan')
                ->where('us.id_client',Auth::user()->id_client)
                ->get();
            $total_jam = DB::table('table_lembur')
                ->selectRaw('SUM(CAST(total_jam as int)) as jam')
                ->where('id_client',Auth::user()->id_client)
                ->first()->jam;
            $kr_lembur = DB::table('table_lembur')->distinct()->where('id_client',Auth::user()->id_client)->count('id_karyawan');
            $wait_lembur = Lembur::where('id_client',Auth::user()->id_client)->where('status','0')->count();
            if(in_array(Auth::user()->id_client,[3,4])) {
                $shift = Shift::where('id_client',Auth::user()->id_client)->get();
                return view("layouts.lembur.vAIOSukabumiAdmin",compact('daftar_kr','kr_lembur','total_jam','wait_lembur','shift'));
            }else {
                return view('layouts.lembur.vLemburDefaultAdmin',compact('daftar_kr','kr_lembur','total_jam','wait_lembur'));
            }
        }else if(Auth::user()->roles == 'spv-internal') {
            if(Auth::user()->id_client == 3) {
                return view('layouts.supervisor.vLemburAIOS');
            }else {
                dd(Auth::user()->id_client);
            }

        }
    }

    function validasi(Request $request) {


        if($request->ttd == '1') {
            if(in_array(Auth::user()->roles,['admin','korlap','hrd','manajer','direktur'])) {
                $ttd = Filemanager::where('id_karyawan',Auth::user()->id_karyawan)->where('slug','signature')->first();

                $data = Lembur::find($request->id_lembur);

                if(Auth::user()->id_client == 1) {
                    if(Auth::user()->roles == 'manajer'){
                        $data->ttd_manager = $ttd->path;
                        $data->status = 1;
                    }else if(Auth::user()->roles == 'hrd'){
                        $data->ttd_admin_korlap = $ttd->path;
                        $data->status = 2;
                    }else if(Auth::user()->roles == 'direktur') {
                        $data->status = 3;
                        $data->ttd_direktur = $ttd->path;
                    }else {
                        $data->status = 1;
                    }
                }else {
                    $data->status = 1;
                    $data->ttd_admin_korlap = $ttd->path;
                }

                $data->update();
                $pesan = ['status' => TRUE,'title' => 'Berhasil ditandatangani' ,'pesan' => 'Lembur '.$data->nama_karyawan.' telah disetujui'];

            }else if(in_array(Auth::user()->roles,['karyawan','kr-pusat'])){
                $ttd = Filemanager::where('id_karyawan',$request->id_karyawan)->where('slug','signature')->first();
                if(in_array(Auth::user()->id_client,['2','1'])) {
                    $data = [
                        'id_karyawan'           => $request->id_karyawan,
                        'nama_karyawan'         => $request->nama_karyawan,
                        'jabatan'               => $request->jabatan,
                        'divisi'                => $request->divisi,
                        'lokasi_kerja'          => $request->lokasi_kerja,
                        'jam_mulai'             => $request->jam_mulai,
                        'jam_selesai'           => $request->jam_selesai,
                        'total_jam'             => $request->jumlah_jam,
                        'alasan_lembur'         => $request->alasan_lembur,
                        'tugas'                 => $request->tugas,
                        'batch'                 => $request->batch,
                        'group'                 => $request->group,
                        'status'                => 0,
                        'tanggal_lembur'        => $request->tanggal_lembur,
                        'id_client'             => Auth::user()->id_client,
                        'ttd_karyawan'          => $ttd->path,
                    ];
                    Lembur::create($data);
                    $pesan = ['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Data berhasil ditambahkan'];
                }else if(in_array(Auth::user()->id_client,['3','4'])){
                    $data = [
                        'id_karyawan'           => $request->id_karyawan,
                        'nama_karyawan'         => $request->nama_karyawan,
                        'jabatan'               => $request->jabatan,
                        'divisi'                => $request->divisi,
                        'lokasi_kerja'          => Auth::user()->id_client,
                        'jam_mulai'             => $request->jam_mulai_ar,
                        'jam_selesai'           => $request->jam_selesai_ar,

                        'jam_mulai_rl'          => $request->jam_mulai_rl,
                        'jam_selesai_rl'        => $request->jam_selesai_rl,

                        'jam_mulai_la'          => $request->jam_mulai_la,
                        'jam_selesai_la'        => $request->jam_selesai_la,

                        'alasan_lembur'         => $request->alasan_lembur,
                        'tugas'                 => $request->tugas,
                        'status'                => 0,
                        'tanggal_lembur'        => $request->tanggal_lembur,
                        'id_client'             => Auth::user()->id_client,
                        'ttd_karyawan'          => $ttd->path,
                        'id_shift'              =>$request->shift,
                    ];
                    Lembur::create($data);
                    $pesan = ['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Data berhasil ditambahkan'];
                }else {
                    $pesan = ['status' => FALSE,'title' => 'Error ' ,'pesan' => 'Terjadi Kesalahan'];
                }
            }else {
                dd(Auth::user()->roles);
            }

        }else {

            $pesan = ['status' => FALSE,'title' => 'Belum ditandatangani' ,'pesan' => 'Tandatangani terlebih dahulu'];
        }

        return response()->json($pesan);

    }

    // function save_data($request) {

    //     $insert = [
    //         'id_karyawan'       =>$request->a,
    //         'nama_karyawan'     =>$request->a,
    //         'jabatan'           =>$request->a,
    //         'divisi'            =>$request->a,
    //         'lokasi_kerja'      =>$request->a,
    //         'tanggal_lembur'    =>$request->a,
    //         'batch'             =>$request->a,
    //         'group'             =>$request->a,
    //         'jam_mulai'         =>$request->a,
    //         'jam_selesai'       =>$request->a,

    //         'jam_mulai_rl'      =>$request->a,
    //         'jam_selesai_rl'    =>$request->a,

    //         'jam_mulai_la'      =>$request->a,
    //         'jam_selesai_la'    =>$request->a,

    //         'total_jam'         =>$request->a,
    //         'alasan_lembur'     =>$request->a,
    //         'tugas'             =>$request->a,
    //         'status'            =>$request->a,
    //         'ttd_admin_korlap'  =>$request->a,
    //         'ttd_karyawan'      =>$request->a,
    //         'id_client'         =>$request->a,
    //     ];
    // }

    function detail(Request $request) {
        $id_lembur  = $request->id;
        $data       = Lembur::find($id_lembur);

        if($data) {
            $pesan = ['status' => TRUE,'data' => $data];
        }else {
            $pesan = ['status' => FALSE];
        }
        return response()->json($pesan);
    }

    function download_data(Request $request) {

        $cek     = Lembur::where('id_karyawan',$request->id_karyawan);

        if($cek != 0) {
            $getData = $cek->whereBetween('tanggal_lembur', [$request->from_date, $request->to_date])->get();
        }
        dd($cek->count(),$request->all());
        // if($cek->count() > 0) {
        //     $data  = $cek->first();
        //     $tanda_tangan_kr        = Signature::where('id_karyawan',$data->id_karyawan)->first();
        //     $tanda_tangan_admin     = Signature::where('id_karyawan',$data->ttd_admin_korlap)->first();
        //     if($data->ttd_admin_korlap != 0) {
        //         $datas                  = Lembur::where('status','>','0')->where('id_karyawan',$request->id_karyawan)->whereBetween('tanggal_lembur', [$request->from, $request->to])->get();
        //         $pdf                    = PDF::loadview("pages.admin.pdf_data_lembur_per_orang",['datas' => $datas,'data' => $data,'tanda_tangan_kr' => $tanda_tangan_kr,'tanda_tangan_admin' => $tanda_tangan_admin]);

        //         $filename = 'Form Lembur '.$data->nama_karyawan.'('.$request->from.'/'.$request->to.')';
        //         $pdf->setPaper('A4', 'landscape');
        //         return $pdf->download($filename.'.pdf');
        //     }else {
        //         return redirect()->route('admin-lembur-index')->with("error",'Anda Belum Mentandatangani Laporan Lembur '.$data->nama_karyawan.'( '.$data->id_karyawan.' )');
        //     }
        // }else {
        //     $data = User::where('karyawan_id',$request->id_karyawan)->first();
        //     return redirect()->route('admin-lembur-index')->with("info",'Data yang ada cari tidak tersedia ');
        // }
    }

    function download_perorang($hash) {
        $enc            = EncryprVariable($hash);
        $data           = Lembur::find($enc);
        if(in_array(Auth::user()->id_client,[3,4])) {
            $update = Lembur::find($enc);
            $update->status = 2;
            $update->update();
            $id_kr_admin    = Filemanager::where('path',$data->ttd_admin_korlap)->where("slug",'signature')->first()->id_karyawan;
            $nama_admin     = User::where('id_karyawan',$id_kr_admin)->first()->name;

            $filename   = 'Surat Perintah Kerja Lembur '.$data->nama_karyawan.'('.$data->tanggal_lembur.')';
            $pdf        = PDF::loadview("layouts.pdf_view.pdfLemburAIOSukabumi",['data' => $data,'nama_admin' => $nama_admin,'filename'=> $filename]);
            $pdf->setPaper('A4', 'potrait');

        }elseif (Auth::user()->id_client == 1){
            $id_kr_admin    = Filemanager::where('path',$data->ttd_admin_korlap)->where("slug",'signature')->first()->id_karyawan;
            $nama_admin     = User::where('id_karyawan',$id_kr_admin)->first()->name;
            $ttd_direktur   = User::where('roles','direktur')->first();
            // dd($ttd_direktur);

            $pdf      = PDF::loadview("layouts.pdf_view.pdfLemburPFI",['data' => $data,'nama_admin' => $nama_admin]);
            $filename = 'Form Lembur '.$data->nama_karyawan;
            $pdf->setPaper('A4', 'landscape');
        }else{
            $id_kr_admin    = Filemanager::where('path',$data->ttd_admin_korlap)->where("slug",'signature')->first()->id_karyawan;
            $nama_admin     = User::where('id_karyawan',$id_kr_admin)->first()->name;

            $pdf      = PDF::loadview("layouts.pdf_view.pdfLemburDefault",['data' => $data,'nama_admin' => $nama_admin]);
            $filename = 'Form Lembur '.$data->nama_karyawan;
            $pdf->setPaper('A4', 'landscape');
        }
        return $pdf->stream($filename.'.pdf');
    }

    function download_multiple($hash, Request $request) {
        $getData = Lembur::where('id_karyawan',$request->id_karyawan)->whereBetween('tanggal_lembur', [$request->from_date, $request->to_date]);
        $dataMaster = Lembur::where('id_karyawan',$request->id_karyawan);

        if($getData->count() != 0) {
            $nama_karyawan  = Karyawan::where('id_karyawan',$request->id_karyawan)->first()->nama_karyawan;
            if(Auth::user()->id_client == 1) {
                $data           = $dataMaster->where('status','>=','3')->get();
                $pdf            = PDF::loadview("layouts.pdf_view.pdfLemburPFIMultiple",['data' => $data,'nama_karyawan' => $nama_karyawan]);

            }else {
                $data           = $dataMaster->where('status','>=','1')->get();
                $pdf            = PDF::loadview("layouts.pdf_view.pdfLemburMultiple",['data' => $data,'nama_karyawan' => $nama_karyawan]);

            }
            $filename       = 'Form Lembur '.$nama_karyawan;
            $pdf->setPaper('A4', 'landscape');


            return $pdf->stream($filename.'.pdf');

            $data = $getData->get();
        }else {
            $data   = "KOSONG";
            $from   = Carbon::parse($request->from_date)->translatedFormat("d F Y");
            $to     = Carbon::parse($request->to_date)->translatedFormat("d F Y");
            return redirect()->back()->with('error','Data Lembur dengan ID Karyawan '.$request->id_karyawan.' dengan tanggal '.$from.' s/d '.$to.' tidak tersedia');
        }
    }

    function upload_files(Request $request){
        $rule = [
            'file_lembur' => 'required|mimes:jpg,jpeg,png,pdf|max:1000',
        ];
        $message = [
            'file_lembur.required'  => "File harus diisi",
            'file_lembur.mimes'     => "File yang diperbolehkan harus berbentuk JPEG,JPG,PNG,PDF",
            'files_lembur.max'      => "File maksimal 1 Mb",
        ];

        $validasi = Validator::make($request->all(),$rule,$message);
        if($validasi->fails()) {
            return response()->json(['status'=>FALSE,'errors'=>$validasi->errors()]);
        }
        $file       = $request->file_lembur;
        $filename   = "Lembur_".date("YmdHi").'.'.$file->getClientOriginalExtension();
        $path       = '/filemanager/data_lembur/';
        $move_folder= $path.$filename;

        $file->move(public_path($path),$filename);

        $createFile = [
            "filename"      => $filename,
            "path"          => $move_folder,
            "extension"     => $file->getClientOriginalExtension(),
            "id_karyawan"   => $request->id_karyawan,
            "slug"          => 'lembur',
            "keterangan"    => 'Lembur Karyawan ',
        ];

        $filemanager = Filemanager::create($createFile);

        $update = Lembur::find($request->id_lembur);
        $update->status = 3;
        $update->id_filemanager = $filemanager->id;
        $update->update();
        return response()->json(['status' => TRUE,'title' => "Sukses" ,'pesan' => "File berhasil diUpload" ]);
    }

    function get_files(Request $request) {
        $lembur  = Lembur::find($request->id);
        $cek     = Filemanager::where('id',$lembur->id_filemanager)->count();
        if($cek != 0) {
            $files =  Filemanager::find($lembur->id_filemanager);
            $get_files =  pathinfo(public_path($files->path.$files->filename), PATHINFO_EXTENSION);

            if($files->extension == 'pdf') {
                $pesan = ['status' => TRUE,'nama_file' => $files->filename,'data'=>$files->filename, 'type_file' => $files->extension , 'links' =>$files->path];
            }else {
                $pesan = ['status' => TRUE,'nama_file' => $files->filename, 'type_file' => $files->extension ,'links' => $files->path];
            }
        }else {
            $pesan = ['status' => TRUE,'pesan' => 'File Tidak Tersedia !'];

        }
        return response()->json($pesan);
    }

    function acc_data(Request $request) {
        $update = Lembur::find($request->id);
        $update->status = 4;
        $update->update();

        $pesan = ['status' => TRUE,'title' => "Sukses !" ,'pesan' => "Laporan Telah Disetujui dan dikirim ke karyawan"];
        return response()->json($pesan);
    }

}

<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use Auth;
use DataTables;
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
use App\Models\Aktfitas;
use Illuminate\Http\Request;

class LemburController extends Controller
{
    function __construct() {
        $this->middleware("auth");
    }

    function index() {

        $cek_divisi = Karyawan::where("id_karyawan",Auth::user()->id_karyawan)->first()->divisi;
        $name_divisi = Divisi::find($cek_divisi)->nama_divisi;

        if(in_array(Auth::user()->roles,['hrd'])) {

            $daftar_kr = User::where('roles','kr-pusat')->orWhere('roles','kr-project')->where('id_client',Auth::user()->id_client)->get();
            $total_jam = DB::table('table_lembur')
                ->selectRaw('SUM(CAST(total_jam as int)) as jam')
                ->where('id_client',Auth::user()->id_client)
                ->first()->jam;
            $lembur = DB::table('table_lembur as al')
                ->join('users as us','us.id_karyawan','=','al.id_karyawan')
                ->where('us.roles','!=','karyawan')
                ->count();
            $wait_acc = Lembur::where('id_client',Auth::user()->id_client)->where('status','0')->count();
            return view('layouts.hrd.vLembur',compact('daftar_kr','lembur','wait_acc'));
        }
        else if(Auth::user()->roles == 'direktur') {
            if($name_divisi == 'MPO'){
                return view('layouts.manajer.vLembur');
            }else {
                return redirect()->route('lembur-direktur');
            }
        }
        else if(in_array(Auth::user()->roles,['kr-pusat','kr-project'])) {
            $datakr         = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first();
            $jabatan        = Jabatan::find($datakr->jabatan)->nama_jabatan;
            $divisi         = Divisi::find($datakr->divisi)->nama_divisi;
            $lokasi_kerja   = Clients::find(Auth::user()->id_client)->nama_client;
            return view('layouts.lembur.vLemburPFI',compact('lokasi_kerja','divisi','jabatan'));

        }
        else if(Auth::user()->roles == 'karyawan'){
            $id_client      = Auth::user()->id_client;
            $data_karyawan  = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first();
            $lokasi_kerja   = Clients::find($data_karyawan->lokasi_kerja)->nama_client;
            $jabatan        = Jabatan::find($data_karyawan->jabatan)->nama_jabatan;
            $divisi         = Divisi::find($data_karyawan->divisi)->nama_divisi;
            $shift          = Shift::where('id_client',$id_client)->get();

            if($id_client == '2') {
                $view = 'layouts.lembur.vLemburDefault';
            }else {
                $view = 'layouts.lembur.vAIOSukabumi';
            }


            return view($view,compact('divisi','jabatan','lokasi_kerja','data_karyawan','shift'));
        }
        else if(in_array(Auth::user()->roles,['admin','korlap'])) {
            $daftar_kr  = DB::table('table_karyawan as tk')
                ->join('users as us','us.id_karyawan','=','tk.id_karyawan')
                ->where('us.roles','karyawan')
                ->where('us.id_client',Auth::user()->id_client)
                ->get();
            // $total_jam = DB::table('table_lembur')
            //     ->selectRaw('SUM(CAST(total_jam as int)) as jam')
            //     ->where('id_client',Auth::user()->id_client)
            //     ->first()->jam;
            $total_jam = "0";
            $kr_lembur = DB::table('table_lembur')->distinct()->where('id_client',Auth::user()->id_client)->count('id_karyawan');
            $wait_lembur = Lembur::where('id_client',Auth::user()->id_client)->where('status','0')->count();
            if(in_array(Auth::user()->id_client,[3,4])) {
                $shift = Shift::where('id_client',Auth::user()->id_client)->get();
                return view("layouts.lembur.vAIOSukabumiAdmin",compact('daftar_kr','kr_lembur','total_jam','wait_lembur','shift'));
            }else {
                return view('layouts.lembur.vLemburDefaultAdmin',compact('daftar_kr','kr_lembur','total_jam','wait_lembur'));
            }
        }
        else if(Auth::user()->roles == 'spv-internal') {
            if(Auth::user()->id_client == 3) {
                return view('layouts.supervisor.vLemburAIOS');
            }else {
                return view('layouts.lembur.vLemburDefaultSPV');
                dd(Auth::user()->id_client);
            }

        }
    }

    function index_self(){
        $datakr         = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first();
        $jabatan        = Jabatan::find($datakr->jabatan)->nama_jabatan;
        $divisi         = Divisi::find($datakr->divisi)->nama_divisi;
        $lokasi_kerja   = Clients::find(Auth::user()->id_client)->nama_client;
        return view('layouts.lembur.vLemburPFI',compact('lokasi_kerja','divisi','jabatan'));
    }

    function validasi(Request $request) {
        $Karyawan   = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first();
        $cek_akun   = $Karyawan->kategori;
        $ttd        = Filemanager::where('id_karyawan',$request->id_karyawan)->where('slug','signature')->first();
        $divisi     = Divisi::find($Karyawan->divisi)->nama_divisi;
        $role       = Auth::user()->roles;
        $cek_ttd    = $request->ttd;

        if($cek_ttd == 1) {
            if(in_array($role,['kr-project','kr-pusat'])) {
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
                $status = ['status' => TRUE,'title'=>'sukses','pesan' => 'Lembur berhasil ditambahkan'];
            }
            else if($role == 'manager'){
                $ttd = Filemanager::where('id_karyawan',Auth::user()->id_karyawan)->where('slug','signature')->first();
                $data = [
                    'status' == 1,
                    'ttd_manajer' => $ttd->path,
                ];
                return $this->update($request->id_lembur,$data);
            }
            else if($role == 'direktur') {
                $ttd = Filemanager::where('id_karyawan',Auth::user()->id_karyawan)->where('slug','signature')->first();

                if($divisi != 'MPO'){
                    $dataUpdate = [
                        'status'    => 4,
                        'ttd_direktur' => $ttd->path,
                    ];

                    return $this->update($request->id_lembur,$dataUpdate);
                }
                else {
                    $data = [
                        'status' => 2,
                        'ttd_manager' => $ttd->path,
                    ];
                    return $this->update($request->id_lembur,$data);
                }
            }
            else if($role == 'hrd'){
                $ttd = Filemanager::where('id_karyawan',Auth::user()->id_karyawan)->where('slug','signature')->first();
                $divisi = Karyawan::where('id_karyawan',$request->id_karyawan)->first()->divisi;
                $nama_divisi = Divisi::find($divisi)->nama_divisi;
                if($nama_divisi == 'MPO') {
                    $dataUpdate = [
                        'status'    => 3,
                        'ttd_admin_korlap' => $ttd->path,
                    ];
                }else {
                    $dataUpdate = [
                        'status'    => 2,
                        'ttd_admin_korlap' => $ttd->path,
                    ];

                }
                return $this->update($request->id_lembur,$dataUpdate);
                // return response($nama_divisi);
            }
            else if($role == 'karyawan') {
                if(in_array(Auth::user()->id_client,[1,2])){
                    $ttd        = Filemanager::where('id_karyawan',Auth::user()->id_karyawan)->where('slug','signature')->first();
                    $data = [
                        'id_karyawan'           => Auth::user()->id_karyawan,
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
                    $status = ['status' => TRUE,'title'=>'sukses','pesan' => 'Lembur berhasil ditambahkan'];
                }else if(in_array(Auth::user()->id_client,[3,4])){
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
                    $status = ['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Data berhasil ditambahkan'];
                }else {
                    $status = ['status' => FALSE,'titlle' => 'ups','pesan' => "Tidak ada aksi"];
                }
            }
            else if(in_array($role, ['admin','korlap'])){
                $ttd        = Filemanager::where('id_karyawan',Auth::user()->id_karyawan)->where('slug','signature')->first();
                $dataUpdate = [
                    'status'    => 1,
                    'ttd_admin_korlap' => $ttd->path,
                    'disetujui_oleh' => Auth::user()->name,
                ];
                return $this->update($request->id_lembur,$dataUpdate);
            }
            else {
                $status = "GAGAL";
            }

        }else {

            $status = FALSE;
        }


        return response()->json($status);

    }

    function update($id,$data) {
        $update = Lembur::where('id',$id)->update($data);
        $data   = Lembur::find($id);
        $pesan = ['status' => TRUE ,'title' => 'sukses'  ,'pesan' => 'Lembur '.$data->nama_karyawan.' telah tandatangani' ];
        Aktivitas(Auth::user()->name."( ".Auth::user()->roles." ) Menandatangani lembur karyawan nama : ".$data->nama_karyawan."-".$data->id_karyawan);
        return response()->json($pesan);

    }

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
            if(Auth::user()->roles == 'kr-project'){
                $id_kr_admin    = Filemanager::where('path',$data->ttd_admin_korlap)->where("slug",'signature')->first()->id_karyawan;
                $nama_admin     = User::where('id_karyawan',$id_kr_admin)->first()->name;
                $ttd_direktur   = User::where('roles','direktur')->first();

                $pdf      = PDF::loadview("layouts.pdf_view.pdfLemburPFI",['data' => $data,'nama_admin' => $nama_admin]);
                $filename = 'Form Lembur '.$data->nama_karyawan;
                $pdf->setPaper('A4', 'landscape');
            }
            else {
                $update = Lembur::find($enc);
                $update->status = 2;
                $update->update();
                $id_kr_admin    = Filemanager::where('path',$data->ttd_admin_korlap)->where("slug",'signature')->first()->id_karyawan;
                $nama_admin     = User::where('id_karyawan',$id_kr_admin)->first()->name;

                $filename   = 'Surat Perintah Kerja Lembur '.$data->nama_karyawan.'('.$data->tanggal_lembur.')';
                $pdf        = PDF::loadview("layouts.pdf_view.pdfLemburAIOSukabumi",['data' => $data,'nama_admin' => $nama_admin,'filename'=> $filename]);
                $pdf->setPaper('A4', 'potrait');

            }

        }elseif (Auth::user()->id_client == 1){
            $id_kr_admin    = Filemanager::where('path',$data->ttd_admin_korlap)->where("slug",'signature')->first()->id_karyawan;
            $nama_admin     = User::where('id_karyawan',$id_kr_admin)->first()->name;
            $ttd_direktur   = User::where('roles','direktur')->first();

            $pdf      = PDF::loadview("layouts.pdf_view.pdfLemburPFI",['data' => $data,'nama_admin' => $nama_admin]);
            $filename = 'Form Lembur '.$data->nama_karyawan;
            $pdf->setPaper('A4', 'landscape');
        }else{
            $id_kr_admin    = Filemanager::where('path',$data->ttd_admin_korlap)->where("slug",'signature')->first()->id_karyawan;
            $nama_admin     = User::where('id_karyawan',$id_kr_admin)->first()->name;
            if($data->divisi == 'MPO') {
                $pdf      = PDF::loadview("layouts.pdf_view.pdfLemburPFI",['data' => $data,'nama_admin' => $nama_admin]);

            }else {
                $pdf      = PDF::loadview("layouts.pdf_view.pdfLemburDefault",['data' => $data,'nama_admin' => $nama_admin]);

            }
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

    function accepted(Request $request ){
        $data = Lembur::find($request->id);
        $data->status = 4;
        $data->disetujui_oleh = 'Direktur HRD';
        $data->update();

        return response()->json(['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Form pengajuan izin '.$data->nama_karyawan.' telah disetujui']);


    }

    function lembur_karyawan($tipe_karyawan) {
        $datakr         = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first();
        $jabatan        = Jabatan::find($datakr->jabatan)->nama_jabatan;
        $divisi         = Divisi::find($datakr->divisi)->nama_divisi;
        $lokasi_kerja   = Clients::find(Auth::user()->id_client)->nama_client;
        if($tipe_karyawan == 'pusat') {
            return view('layouts.spv.vLemburPusat',compact('lokasi_kerja','divisi','jabatan'));
        }else if($tipe_karyawan == 'project') {
            return view('layouts.spv.vLemburProject',compact('lokasi_kerja','divisi','jabatan'));
        }else  {
            abort(404);
        }
    }

    function lembur_karyawan_data(Request $request) {
        $data = Lembur::where('divisi','MPO')->where('id_client',Auth::user()->id_client)->get();
        if($request->filled('from_date') || $request->filled('to_date')){
            $data = $data->whereBetween('tanggal_lembur', [$request->from_date, $request->to_date]);
        }
        $dt = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function($row) {
                $edit   = '<a href="javascript:void(0)" class="btn btn-primary btn-sm" id="acc_'.$row->id.'" onclick="acc('.$row->id.')"  ><i class="bx bx-check-circle"></i>Setujui</a>';
                $hapus  = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" id="hapus_'.$row->id.'" onclick="hapus('.$row->id.')" ><i class="bx bxs-trash" ></i>Hapus</a>';
                if($row->status == 0) {
                    return $edit;
                }else {
                    return "";
                };
            })
            ->addColumn('status', function($row) {
                $acc_spv        =   "<span class='badge bg-warning'> Menuggu Persetujuan Supervisor </span>";
                $manager_divisi =   "<span class='badge bg-warning'> Menuggu Ditandatangani Manager Divisi </span>";
                $spv_hrd        =   "<span class='badge bg-warning'> Menuggu Ditandatangani HRD </span>";
                $dir_hrd        =   "<span class='badge bg-warning'> Menuggu Ditandatangani Direktur HRD </span>";
                $acc            =   "<span class='badge bg-success'> Telah disetujui </span>";
                if($row->status == 0 ) {
                    $st = $acc_spv;
                }else if($row->status == 1 ) {
                    $st = $manager_divisi;
                }else if($row->status == 2) {
                    $st = $spv_hrd;
                }else if($row->status == 3) {
                    $st =  $dir_hrd  ;
                }else if($row->status == 4) {
                    $st = $acc;
                }
                else {
                    $st = "<span class='badge bg-danger'> Error Status </span>";

                }
                return $st;
            })
            ->addColumn("tanggal_lembur", function($row) {
                return Carbon::parse($row->tanggal_lembur)->translatedFormat('d F Y');
            })
            ->rawColumns(['aksi','status','disetujui_oleh'])
            ->make(true);

            return $dt;


    }

    function lembur_hrd_data(Request $request) {
        $data = Lembur::where('id_client',1)->get();

        $dt = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function($row) {
                $edit   = '<a href="javascript:void(0)" class="btn btn-primary btn-sm" id="edit_'.$row->id.'" onclick="detail('.$row->id.')"  ><i class="bx bx-edit-alt"></i>Detail</a>';
                $hapus  = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" id="hapus_'.$row->id.'" onclick="hapus('.$row->id.')" ><i class="bx bxs-trash" ></i>Hapus</a>';
                $file   = '<a href="'.route("lembur-download-perorang",['hash' => HashVariable($row->id)]).'" class="btn btn-danger btn-sm" ><i class="bx bx-download"></i> Lihat File</a>';

                if($row->divisi == 'MPO') {
                    if($row->status == 2){
                        return $edit;
                    }
                    else if($row->status == 4) {
                        return $file;
                    }else {
                        return "";
                    }
                }else {
                    if($row->status == 1) {
                        return $edit;

                    }
                    else if($row->status == 4){
                        return $file;
                    }
                    else {
                        return "";
                    }
                }
            })
            ->addColumn('status', function($row) {
                if($row->divisi == 'MPO') {
                    $acc_spv        =   "<span class='badge bg-warning'> Menuggu Persetujuan Supervisor </span>";
                    $manager_divisi =   "<span class='badge bg-warning'> Menuggu Ditandatangani Manager Divisi </span>";
                    $spv_hrd        =   "<span class='badge bg-warning'> Menuggu Ditandatangani HRD </span>";
                    $dir_hrd        =   "<span class='badge bg-warning'> Menuggu Ditandatangani Direktur HRD </span>";
                    $acc            =   "<span class='badge bg-success'> Telah disetujui </span>";
                    if($row->status == 0 ) {
                        $st = $acc_spv;
                    }else if($row->status == 1 ) {
                        $st = $manager_divisi;
                    }else if($row->status == 2) {
                        $st = $spv_hrd;
                    }else if($row->status == 3) {
                        $st =  $dir_hrd  ;
                    }else if($row->status == 4) {
                        $st = $acc;
                    }
                    else {
                        $st = "<span class='badge bg-danger'> Error Status </span>";

                    }

                }else {
                    $wait           = "<span class='badge bg-warning'> Menuggu Ditandatangani Manager Divisi </span>";
                    $waitHRD        = "<span class='badge bg-warning'> Menuggu Ditandatangani HRD </span>";
                    $waitDirektur   = "<span class='badge bg-warning'> Menuggu Ditandatangani Direktur HRD </span>";
                    if($row->status == 0 ) {
                        $st = $wait;
                    }else if($row->status == 1 ) {
                        $st = $waitHRD;
                    }else if($row->status == 2) {
                        $st = $waitDirektur;
                    }else if($row->status == 3) {
                        $st = "<span class='badge bg-warning'> Menunggu Persetujuan </span>";

                    }else {
                        $st = "<span class='badge bg-success'> Telah disetujui </span>";

                    }
                }
                return $st;
            })
            ->addColumn("tanggal_lembur", function($row) {
                return Carbon::parse($row->tanggal_lembur)->translatedFormat('d F Y');
            })
            ->rawColumns(['aksi','status','disetujui_oleh'])
            ->make(true);

            return $dt;

    }

    function update_status(Request $request) {
        $id = $request->id;
        $update = Lembur::find($id);
        $update->status = $request->status;
        $update->update();
        Aktivitas(Auth::user()->name.' ('.Auth::user()->roles.').  Menyetujui permintaan lembur '.$update->nama_karyawan);
        return response()->json([
            'status' => TRUE,
            'title' => 'Sukses',
            'pesan' => $update->nama_karyawan.' telah disetujui'
        ]);

    }

    function data_lembur_kr_internal_project(Request $request,$hash) {
        $id_karyawan = EncryprVariable($hash);
        $data        = Lembur ::where('id_karyawan',$id_karyawan)->get();
        $result      = [];

        foreach($data as $key) {
            $result[]   =   [
                'id'            => $key['id'],
                'id_karyawan'   => $key['id_karyawan'],
                'tanggal_lembur'=> Carbon::parse($key['tanggal_lembur'])->translatedFormat("l, d F Y"),
                'jam_mulai'     => $key['jam_mulai'],
                'jam_selesai'   => $key['jam_selesai'],
                'tugas'         => $key['tugas'],
                'total_jam'     => $key['total_jam'],
                'status'        => $this->info_status($key['divisi'],$key['status']),
                'disetujui_oleh'=> $key['disetujui_oleh'],
                'aksi'          => $this->aksi($key['divisi'],$key['status'],$key['id'],Auth::user()->roles),

            ];
        }

        $dt = DataTables::of($result)
        ->addIndexColumn()
        ->rawColumns(['status','aksi'])
        ->make(true);

        return $dt;
        dd($result);
    }

    function info_status($divisi,$status) {
        if($divisi == 'MPO') {
            if($status == 0) {
                $s = '<span class="badge bg-warning">Menunggu Disetujui Supervisor</span>';
            }else if($status == 1) {
                $s = '<span class="badge bg-warning">Menunggu Ditanda Tangani Manager Divisi</span>';
            }else if($status == 2) {
                $s = '<span class="badge bg-warning">Menunggu Ditanda Tangani HRD</span>';
            }else if ($status == 3) {
                $s = '<span class="badge bg-warning">Menunggu Ditanda Tangani Direktur HRD</span>';
            }else if($status == 4) {
                $s = '<span class="badge bg-warning">Menunggu Disetujui Direktur HRD</span>';
            }else if($status == 5 ){
                $s = '<span class="badge bg-success">Sudah Ditanda Tangani </span>';
            }
            else {
                $s = '<span class="badge bg-danger">Status tidak diketahui</span>';
            }
        }
        else {
            if($status == 0) {
                $s = '<span class="badge bg-warning">Menunggu Ditanda Tangani Manager Divisi</span>';
            }else if($status == 1) {
                $s = '<span class="badge bg-warning">Menunggu Ditanda Tangani HRD</span>';
            }else if($status == 2) {
                $s = '<span class="badge bg-warning">Menunggu Ditanda Tangani Direktur HRD</span>';
            }else if ($status == 3) {
                $s = '<span class="badge bg-warning">Menunggu Disetujui Direktur HRD</span>';
            }else if($status == 4) {
                $s = '<span class="badge bg-success">Sudah Ditanda Tangani </span>';

            }
            else {
                $s = 'Status tidak diketahui';
            }

        }

        return $s;

    }

    function aksi($divisi,$status,$id,$roles) {
        $edit   = '<a href="javascript:void(0)" class="btn btn-primary btn-sm" id="edit_'.$id.'" onclick="detail('.$id.')"  ><i class="bx bx-edit-alt"></i>Edit</a>';
        $file   = '<a href="'.route("lembur-download-perorang",['hash' => HashVariable($id)]).'" class="btn btn-primary btn-sm" ><i class="bx bx-download"></i> Lihat File</a>';
        $hapus  = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" id="hapus_'.$id.'" onclick="hapus('.$id.')" ><i class="bx bxs-trash" ></i>Hapus</a>';

        if(in_array($roles,['kr-project','kr-pusat'])){
            if($divisi == 'MPO') {
                if($status >= 0 ||$staus <= 4 ) {
                    $s = $edit.'&nbsp;'.$hapus;
                }else {
                    $s = $file;
                }
            }else {
                if($status >= 0 ||$staus <= 3 ) {
                    $s = $edit.'&nbsp;'.$hapus;
                }else  {
                    $s = $file;
                }
            }
        }else {
            $s = "";
        }
        return $s;
    }

    function delete_lembur($id) {
        $data = Lembur::findOrFail($id);
        $data->delete();
        return response()->json(['status' => TRUE,'pesan' => "Data lembur berhasil dihapus" ]);
    }

}

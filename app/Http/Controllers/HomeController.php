<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Models\Karyawan;
use App\Models\Divisi;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Filemanager;
use App\Models\Clients;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $roles = Auth::user()->roles;
        $query =DB::table('table_pengumuman')
                    ->where([
                        'id_client' => Auth::user()->id_client,
                        'arsip'     => 0
                    ])
                    // ->where('arsip',0)
                    ->orderBy('created_at', 'desc');

        if($roles == 'karyawan') {
            $queryWhere = $query->where('views',1)->orWhere('views',2);
            $pengumuman = $queryWhere->take(4)->get();
        }
        else if($roles != 'karyawan'){
            $queryWhere = $query->where('views',1)->orWhere('views',3);
            $pengumuman = $queryWhere->take(4)->get();
        }
        else {
            $pengumuman = "";
        }


        $table_kr   = Karyawan::where('id_karyawan','like','%'.Auth::user()->id_karyawan.'')->first();

        $data = [
            'pengumuman'
        ];
        if(Auth::user()->roles == 'superadmin'){
            $dataKr = Karyawan::count();
            return view('layouts.Dashboard.vSuperadmin',compact('dataKr'));

        }
        elseif (Auth::user()->roles == 'spv-internal') {
            $view = 'layouts.Dashboard.vSupervisorInternal';


        }
        elseif (Auth::user()->roles == 'karyawan') {
            $view = 'layouts.Dashboard.vKaryawanProject';

        }
        else if(Auth::user()->roles == 'direktur'){
            $divisi = Divisi::find(Auth::user()->karyawan->divisi)->nama_divisi;
            if($divisi == 'MPO') {
                $view = 'layouts.Dashboard.vDirekturMPO';
                $client = Clients::where('id','!=',1)->get();
                $AddData = ['client' => $client ];
                $total_karyawan = [];
                foreach ($client as $key ) {
                    $total_karyawan[] = [
                        'nama_client' => $key->nama_client,
                        'total'   => User::where('id_client',$key->id)->count()
                    ];
                }
                // dd($total_karyawan);
               array_push($data,'total_karyawan');
            }
        }
        else {
            $view = 'layouts.Dashboard.vHome';
        }
        return view($view,compact($data));
    }

    function checking_tanda_tangan(Request $request) {
        $id_karyawan = $request->id_karyawan;
        $search = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','signature')->count();

        if($search >= 1) {
            $ttd = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','signature')->first();
            $status = ['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Berhasil ditandatangani','path' => $ttd->path];
        }else {

            $status = ['status' => FALSE,'title' => 'Error',' pesan' => "Terjadi kesalahan hubungi tim IT"];
        }
        return response()->json($status);
    }

    function save_tanda_tangan(Request $request) {
        ob_start();
            $request->svg;
        $ttd = ob_get_clean();

        $filename   = Auth::user()->id_karyawan.'TTD.svg';
        $path       = 'assets/img/signature/'.$filename;
        $save_svg   = file_put_contents(public_path('assets/img/signature/').$filename,$request->svg);
        $save = [
            'filename'  => $filename,
            'path'      => $path,
            'extension' => 'svg',
            'id_karyawan'=> Auth::user()->id_karyawan,
            'slug'       =>'signature',
            'keterangan' => 'Tanda Tangan '.Auth::user()->name.'',
        ];

        $saving = Filemanager::create($save);

        if($saving){
            $status =['status' => TRUE,'title' => 'sukses', 'pesan' => 'Tanda tangan berhasil ditambahkan','file' => $saving->path];
        }else {
            $status = ['status' => FALSE ,'title' => 'Error',' pesan' => "Terjadi kesalahan hubungi tim IT"];
        }
        return response()->json($status);
    }
}

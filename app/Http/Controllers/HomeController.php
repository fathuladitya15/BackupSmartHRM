<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Karyawan;
use App\Models\Divisi;
use Carbon\Carbon;
use App\Models\Filemanager;
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
        // dd($roles);

        // if($roles == 'admin') {
        //     return view('layouts.vSelect');
        // }
        // dd(Carbon::now()->translatedFormat('d F Y'));
        // dd(Auth::user()->roles);
        $table_kr   = Karyawan::where('id_karyawan','like','%'.Auth::user()->id_karyawan.'')->first();
        // dd($table_kr);
        if(Auth::user()->roles == 'superadmin'){
            // dd("TES");
            $dataKr = Karyawan::count();
            return view('layouts.Dashboard.vSuperadmin',compact('dataKr'));

        }else {

            return view('layouts.Dashboard.vHome');
        }
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

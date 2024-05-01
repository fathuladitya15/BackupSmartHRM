<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Izin;
use App\Models\Divisi;
use App\Models\Lembur;
use App\Models\Clients;
use App\Models\Karyawan;
use App\Models\Pengumuman;
use App\Models\Filemanager;
use App\Models\Peringatan;
use App\Models\Aktifitas;
use Illuminate\Http\Request;
use App\Notifications\NotifikasiSelesaiKontrak;
use App\Events\ChatSent;

use Illuminate\Support\Facades\Storage;
// use Intervention\Image\ImageManagerStatic as Image;


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

    public function index()
    {

        $roles = Auth::user()->roles;

        $query =DB::table('table_pengumuman')
                    ->where([
                        'id_client' => Auth::user()->id_client,
                        'arsip'     => 0
                    ])
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
            $khl = Karyawan::where('gol_karyawan','KHL')->whereDate('end_date',Carbon::now()->addDays(7))->get();
            $pkwt = Karyawan::where('gol_karyawan','PKWT')->get();
            // dd($pkwt);

            foreach ($khl as $kr_khl ) {
                $kr_khl->notify(new NotifikasiSelesaiKontrak());
            }

            return view('layouts.Dashboard.vSuperadmin',compact('dataKr'));

        }
        elseif (Auth::user()->roles == 'spv-internal') {
            $kr_project     = User::where('id_client',Auth::user()->id_client)->where('roles','karyawan')->count();
            $kr_internal    = Karyawan::where('lokasi_kerja',Auth::user()->id_client)->where('kategori','project')->count();
            $view           = 'layouts.Dashboard.vSupervisorInternal';
            $riwayat        = Aktifitas::where("id_client",Auth::user()->id_client)->get();
            $suratPeringatan= Peringatan::where('lokasi_kerja',Auth::user()->id_client)->where('status',1)->count();
            array_push($data,'kr_project','kr_internal','riwayat');
        }
        elseif (Auth::user()->roles == 'karyawan') {
            $firstDate  = Carbon::now()->startOfMonth();
            $EndDate    = Carbon::now()->endOfMonth();
            $lembur     = Lembur::where("id_karyawan",Auth::user()->id_karyawan)
                        ->whereBetween('tanggal_lembur', [$firstDate->format('Y-m-d'), $EndDate->format('Y-m-d')])->count();
            $izin       = Izin::where("karyawan_id",Auth::user()->id_karyawan)
                        ->whereBetween('tanggal_pembuatan', [$firstDate->format('Y-m-d'), $EndDate->format('Y-m-d')])->count();

            $view = 'layouts.Dashboard.vKaryawanProject';
            // dd($izin);
            array_push($data,'lembur','izin');
        }
        else if(Auth::user()->roles == 'direktur'){
            $divisi     = Divisi::find(Auth::user()->karyawan->divisi)->nama_divisi;
            $view       = 'layouts.Dashboard.vDirekturMPO';
            $client     = Clients::where('id','!=',1)->get();
            $AddData    = ['client' => $client ];
            $total_karyawan = [];
            foreach ($client as $key ) {
                $total_karyawan[] = [
                    'nama_client' => $key->nama_client,
                    'total'   => User::where('id_client',$key->id)->where('roles','karyawan')->count()
                ];
            }
            $kr_project = Karyawan::where('kategori','project')->count();
            $kr_pusat = Karyawan::where('kategori','pusat')->count();
           array_push($data,'total_karyawan','kr_project','kr_pusat');
            // if($divisi == 'MPO') {
            // }
        }
        elseif (in_array(Auth::user()->roles ,['admin','korlap'])) {
            $izin       = DB::table("table_izin as ti")
                            ->join('table_karyawan as us','us.id_karyawan','=','ti.karyawan_id')
                            ->where('ti.status','0')
                            ->where('ti.id_client','LIKE','%'.Auth::user()->id_client.'%')
                            ->count();
            $lembur      = DB::table("table_lembur as ti")
                            ->join('table_karyawan as us','us.id_karyawan','=','ti.id_karyawan')
                            ->where('ti.status','0')
                            ->where('ti.id_client','LIKE','%'.Auth::user()->id_client.'%')
                            ->count();
            $totalkr    = User::where('id_client',Auth::user()->id_client)->where('roles','karyawan')->count();
            $total      = $izin + $lembur;
            $view       = 'layouts.Dashboard.vAdminKorlap';
            array_push($data,'total','totalkr');
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

    function  save_tanda_tangan  (Request $request) {
        $ttd = $request->svg;
        list($type, $data) = explode(';', $ttd);
        list(, $data) = explode(',', $data);

        // Konversi base64 menjadi binary
        $image      = base64_decode($data);
        $filename   = uniqid().Auth::user()->id_karyawan.'TTD.png';
        $path       = 'assets/img/signature/'.$filename;

        $save_svg   = file_put_contents(public_path('assets/img/signature/').$filename,$image);
        $save = [
            'filename'  => $filename,
            'path'      => $path,
            'extension' => 'png',
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

    function sendMessage(Request $request) {
        broadcast(new ChatSent('Test Pesan'));
    }
}

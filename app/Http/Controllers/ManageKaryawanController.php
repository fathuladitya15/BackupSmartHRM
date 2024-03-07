<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Validator;
use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Role;
use App\Models\User;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Clients;
use App\Models\Karyawan;
use App\Models\Filemanager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManageKaryawanController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }

    private function upload_file($data) {

        $file       = $data['file'];
        $keterangan = $data['keterangan'];
        $filename   = $data['filename'];
        $path       = $data['path'];
        $id_karyawan= $data['id_karyawan'];
        $slug       = $data['slug'];
        $extension  = $data['extension'];

        $file->move(public_path($data['path']),$data['filename']);

        $createFilemanager = [
            "filename"      => $filename,
            "path"          => $path,
            "extension"     => $extension,
            "id_karyawan"   => $id_karyawan,
            "slug"          => $slug,
            "keterangan"    => $keterangan,
        ];

        Filemanager::create($createFilemanager);

    }

    function index() {
        $tipe_akun  = Auth::user()->roles;

        if($tipe_akun == 'superadmin'){
            $link_data = route('data-kr-superadmin');
            return view('layouts.superadmin.vKaryawan',compact('link_data'));
        }else if($tipe_akun == 'hrd') {
            $link_data = route('data-kr-hrd');
            return view('layouts.hrd.vKaryawan',compact('link_data'));
        }else if(in_array($tipe_akun,['admin','korlap'])) {
            $link_data = route('data-kr-admin');
            return view('layouts.admin_korlap.vKaryawan',compact('link_data'));
        }else if(in_array($tipe_akun,['spv-internal'])) {
            // return view('layouts.supervisor.vLembur');
            // dd($tipe_akun);

        }
    }

    function create() {
        $tipe_akun  = Auth::user()->roles;
        $bank       = Bank::all();

        if($tipe_akun == 'superadmin'){
            $jabatan    = Jabatan::where('id_client',1)->get();
            $divisi     = Divisi::where('id_client',1)->get();
            $tipe_akun  = Role::all();
            $client     = Clients::all();

            return view('layouts.superadmin.vKaryawanAdd',compact('bank','client','jabatan','divisi','tipe_akun'));
        }else if($tipe_akun == 'hrd'){
            $jabatan    = Jabatan::where('id_client',1)->orderBy('nama_jabatan','ASC')->get();
            $divisi     = Divisi::where('id_client',1)->orderBy('nama_divisi','ASC')->get();
            $tipe_akun  = Role::where('slug_role','!=','superadmin')->where('slug_role','!=','karyawan')->orderBy('name_role','ASC')->get();
            $client     = Clients::orderBy('nama_client','ASC')->get();

            return view('layouts.hrd.vKaryawanAdd',compact('bank','client','jabatan','divisi','tipe_akun'));

        }else {
            $jabatan    = Jabatan::where('id_client',Auth::user()->id_client)->get();
            $divisi     = Divisi::where('id_client',Auth::user()->id_client)->get();
            $nama_client= Clients::find(Auth::user()->id_client)->nama_client;
            $client     = Clients::where('nama_client','Like','%'.$nama_client.'%')->get();

            return view('layouts.admin_korlap.vKaryawanAddDefault',compact('bank','client','jabatan','divisi','tipe_akun'));

            // dd(Auth::user()->id_client);
        }
    }

    function store(Request $request) {
        $tipe_akun  = Auth::user()->roles;

        if($tipe_akun == 'superadmin') {
           return $this->store_superadmin($request);
        }else if($tipe_akun == 'hrd') {

            return $this->validate_hrd($request);
        }else if(in_array($tipe_akun,['admin','korlap'])){
           return $this->store_superadmin($request);
        }else {
            dd($request->all());
        }
    }

    function store_superadmin($request) {

        $validator = [
            'nohp'               => 'min:10|unique:table_karyawan,no_hp',
            'email'              => 'required|email|unique:users,email',
            'profile_pictures'   => 'mimes:jpg,jpeg,png|max:1000',
            'ktp'                => 'mimes:jpg,jpeg,png|max:1000',
            'npwp'               => 'mimes:jpg,jpeg,png|max:1000',
            'kpj'                => 'mimes:jpg,jpeg,png|max:1000',
            'jkn'                => 'mimes:jpg,jpeg,png|max:1000',
            'cv'                 => 'mimes:jpg,jpeg,png|max:1000',
            'lainnya'            => 'mimes:jpg,jpeg,png|max:1000',
            'n_ktp'              => 'digits:16|unique:table_karyawan,nik',
            'nomer_rek_bank'     => 'unique:table_karyawan,norek_bank'
        ];

        $message = [
            'nohp.min'              => 'Nomor Handphone minimal 10 digit',
            'nohp.unique'           => 'Nomor Handphone sudah digunakan',
            'email.unique'          => 'Email sudah digunakan',
            'n_ktp.unique'          => 'Nomor Induk Kependudukan sudah digunakan',
            'nomer_rek_bank.unique' => 'Nomor rekening sudah digunakan',
            'ktp.mimes'             => "File harus dalam bentuk JPG, PNG, JPEG",
            'npwp.mimes'            => "File harus dalam bentuk JPG, PNG, JPEG",
            'jkn.mimes'             => "File harus dalam bentuk JPG, PNG, JPEG",
            'kpj.mimes'             => "File harus dalam bentuk JPG, PNG, JPEG",
            'cv.mimes'              => "File harus dalam bentuk JPG, PNG, JPEG, PDF",
            'lainnya.mimes'         => "File harus dalam bentuk PDF, dan digabungkan jika file lebih dari 1",
            'profile_pictures.mimes'=> "Foto Profile harus dalam bentuk JPG, PNG, JPEG",
            'ktp.max'             => "File maksimal 1 Mb",
            'npwp.max'            => "File maksimal 1 Mb",
            'jkn.max'             => "File maksimal 1 Mb",
            'kpj.max'             => "File maksimal 1 Mb",
            'cv.max'              => "File maksimal 1 Mb",
            'lainnya.max'         => "File maksimal 1 Mb",
            'profile_pictures.max'=> "Foto Profile maksimal 1 Mb",
            'n_ktp.digits'        => "Nomor Induk Kependudukan harus 16 angka",

        ];

        $validasi = Validator::make($request->all(),$validator,$message);

        if($validasi->fails()){
            return redirect()->back()->withErrors($validasi)->withInput();
        }else {
            return $this->create_database($request);
        }

    }

    function validate_hrd($request) {

        $validator = [
            'username'           => 'unique|users,username',
            'id_karyawan'        => 'unique|users,id_karyawan',
            'nohp'               => 'min:10|unique:table_karyawan,no_hp',
            'email'              => 'required|email|unique:users,email',
            'profile_pictures'   => 'mimes:jpg,jpeg,png|max:1000',
            'ktp'                => 'mimes:jpg,jpeg,png|max:1000',
            'npwp'               => 'mimes:jpg,jpeg,png|max:1000',
            'kpj'                => 'mimes:jpg,jpeg,png|max:1000',
            'jkn'                => 'mimes:jpg,jpeg,png|max:1000',
            'cv'                 => 'mimes:jpg,jpeg,png|max:1000',
            'lainnya'            => 'mimes:jpg,jpeg,png,pdf|max:1000',
            'n_ktp'              => 'digits:16|unique:table_karyawan,nik',
            'nomer_rek_bank'     => 'unique:table_karyawan,norek_bank'
        ];

        $message = [
            'username.unique'              => 'Username sudah terpakai',
            'id_karyawan.unique'           => 'ID Karyawan Sudah terdaftar',
            'nohp.min'              => 'Nomor Handphone minimal 10 digit',
            'nohp.unique'           => 'Nomor Handphone sudah digunakan',
            'email.unique'          => 'Email sudah digunakan',
            'n_ktp.unique'          => 'Nomor Induk Kependudukan sudah digunakan',
            'nomer_rek_bank.unique' => 'Nomor rekening sudah digunakan',
            'ktp.mimes'             => "File harus dalam bentuk JPG, PNG, JPEG",
            'npwp.mimes'            => "File harus dalam bentuk JPG, PNG, JPEG",
            'jkn.mimes'             => "File harus dalam bentuk JPG, PNG, JPEG",
            'kpj.mimes'             => "File harus dalam bentuk JPG, PNG, JPEG",
            'cv.mimes'              => "File harus dalam bentuk JPG, PNG, JPEG, PDF",
            'lainnya.mimes'         => "File harus dalam bentuk PDF, dan digabungkan jika file lebih dari 1",
            'profile_pictures.mimes'=> "Foto Profile harus dalam bentuk JPG, PNG, JPEG",
            'ktp.max'             => "File maksimal 1 Mb",
            'npwp.max'            => "File maksimal 1 Mb",
            'jkn.max'             => "File maksimal 1 Mb",
            'kpj.max'             => "File maksimal 1 Mb",
            'cv.max'              => "File maksimal 1 Mb",
            'lainnya.max'         => "File maksimal 1 Mb",
            'profile_pictures.max'=> "Foto Profile maksimal 1 Mb",
            'n_ktp.digits'        => "Nomor Induk Kependudukan harus 16 angka",

        ];

        $validasi = Validator::make($request->all(),$validator,$message);

        if($validasi->fails()){
            return redirect()->back()->withErrors($validasi)->withInput();
        }else {
            return $this->create_database($request);
        }
    }

    function create_database($request){
        $dataUsers = [
            'name'      => $request->name,
            'username'  => $request->karyawan_id,
            'email'     => $request->email,
            'id_karyawan' => $request->karyawan_id,
            'roles'     =>  $request->tipe_akun,
            'password'  => Hash::make($request->password),
            'id_client' => $request->branch,
        ];

        $crateUsers = User::create($dataUsers);

        $dataKaryawan = [
            'nama_karyawan'     => $request->name,
            'id_karyawan'       => $request->karyawan_id,
            'no_hp'             => $request->karyawan_id,
            'tanggal_lahir'     => $request->tglLhr,
            'tempat_lahir'      => $request->TmptLhr,
            'jenis_kelamin'     => $request->jk,
            'nik'               => $request->n_ktp,
            'no_kk'             => 0,
            'alamat'            => $request->alamat,
            'alamat_domisili'   => $request->alamat_domisili,
            'lokasi_kerja'      => $request->branch,
            'divisi'            => $request->divisi,
            'jabatan'           => $request->jabatan,
            'join_date'         => $request->join_date,
            'end_date'          => $request->end_date,
            'no_sio'            => $request->noSio,
            'gol_karyawan'      => $request->gol_karyawan,
            'marital'           => $request->marital,
            'tJabatan'          => $request->tJabatan,
            'tTransport'        => $request->tTransport,
            'nama_bank'         => $request->nama_bank,
            'cabang_bank'       => $request->bank_cabang,
            'kd_bank'           => $request->kd_bank,
            'norek_bank'        => $request->nomer_rek_bank,
            'status'            => $request->status_account,
            'status_karyawan'   => null,
            'cuti'              => $request->cuti,
            'usia'              => $request->usia,
            'nama_s_i'          => $request->suami_istri,
            'jmlh_anak'         => $request->jmlh_anak,
            'nama_ibu'          => $request->nama_ibu,
            'nama_anak'         => $request->nama_anak,
            'nama_bapa'         => $request->nama_bapak,
            'pendidikan'        => $request->pendidikan,
            'agama'             => $request->agama,
            'kategori'          => $request->kategori_pengguna,
            'pencairan_bpjstk'  => $request->pencairan_bpjstk,
            'no_npwp'           => $request->n_npwp,
            'no_kpj'            => $request->n_kpj,
            'no_jkn'            => $request->jkn,
        ];

        if($crateUsers) {

            Karyawan::create($dataKaryawan);

            if($request->hasFile('profile_pictures')){
                $file       = $request->profile_pictures;
                $filename   = "PP_".date("YmdHi").'.'.$file->getClientOriginalExtension();
                $path       = '/filemanager/photo_profile/';
                $move_folder= $path.$filename;

                $data_upload = [
                    'file'      => $file,
                    'filename'  => $filename,
                    'path'      => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'id_karyawan' => $request->karyawan_id,
                    'slug'          => 'foto_profile',
                    'keterangan' => 'foto Profile '.$request->name.' '
                ];

                $this->upload_file($data_upload);
            }

            if($request->hasFile('ktp')){
                $file       = $request->ktp;
                $filename   = "KTP_".date("YmdHi").'.'.$file->getClientOriginalExtension();
                $path       = '/filemanager/ktp/';
                $move_folder= $path.$filename;

                $data_upload = [
                    'file'      => $file,
                    'filename'  => $filename,
                    'path'      => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'id_karyawan' => $request->karyawan_id,
                    'slug'      => 'ktp',
                    'keterangan'=> 'KTP '.$request->name
                ];

                $this->upload_file($data_upload);
            }

            if($request->hasFile('jkn')){
                $file       = $request->jkn;
                $filename   = "JKN_".date("YmdHi").'.'.$file->getClientOriginalExtension();
                $path       = '/filemanager/jkn/';
                $move_folder= $path.$filename;

                $data_upload = [
                    'file'      => $file,
                    'filename'  => $filename,
                    'path'      => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'id_karyawan' => $request->karyawan_id,
                    'slug'      => 'jkn',
                    'keterangan'=> 'JKN '.$request->name
                ];

                $this->upload_file($data_upload);
            }

            if($request->hasFile('kpj')){
                $file       = $request->kpj;
                $filename   = "kpj_".date("YmdHi").'.'.$file->getClientOriginalExtension();
                $path       = '/filemanager/kpj/';
                $move_folder= $path.$filename;

                $data_upload = [
                    'file'      => $file,
                    'filename'  => $filename,
                    'path'      => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'id_karyawan' => $request->karyawan_id,
                    'slug'      => 'kpj',
                    'keterangan'=> 'kpj '.$request->name
                ];

                $this->upload_file($data_upload);
            }

            if($request->hasFile('npwp')){
                $file       = $request->npwp;
                $filename   = "npwp_".date("YmdHi").'.'.$file->getClientOriginalExtension();
                $path       = '/filemanager/npwp/';
                $move_folder= $path.$filename;

                $data_upload = [
                    'file'      => $file,
                    'filename'  => $filename,
                    'path'      => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'id_karyawan' => $request->karyawan_id,
                    'slug'      => 'npwp',
                    'keterangan'=> 'npwp '.$request->name
                ];

                $this->upload_file($data_upload);
            }

            Aktivitas("Melakukan Pembuatan Karyawan baru dengan nama : ".$request->name."-".$request->karyawan_id.", pada tanggal ".Carbon::now()->translatedFormat("d F Y")."  ");

            return redirect()->route('karyawan')->with('success','Berhasil menambahkan '.$request->name.'');
        }
    }

    function edit($hash) {
        $id_karyawan = EncryprVariable($hash);

        $data_karyawan  = Karyawan::where('id_karyawan',$id_karyawan)->first();
        $data_users     = User::where('id_karyawan',$id_karyawan)->first();

        $count = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','foto_profile')->count();
        if($count >= 1 )
        {
            $data_profile = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','foto_profile')->first();
            $path = asset($data_profile->path.$data_profile->filename) ;
        }else {
            $jk = $data_karyawan->jenis_kelamin;
            if($jk == 'L') {
                $path = asset('assets/img/avatars/1.png');
            }else {
                $path = asset('assets/img/avatars/6.png');
            }
        }

        $view  = 'layouts.akun.akun_setting';
        return view('layouts.akun.index',compact('data_users','path','view','data_karyawan'));
        // dd($data_users);
    }

    function pribadi($hash) {
        $id_karyawan = EncryprVariable($hash);

        $data_karyawan  = Karyawan::where('id_karyawan',$id_karyawan)->first();
        $data_users     = User::where('id_karyawan',$id_karyawan)->first();
        $view           = 'layouts.akun.data_pribadi_default';
        return view('layouts.akun.index',compact('data_users','view','data_karyawan'));

    }
    function client($hash) {
        $id_karyawan    = EncryprVariable($hash);
        $data_users     = User::where('id_karyawan',$id_karyawan)->first();
        // dd($data_users);
        $nama_client    = Clients::find($data_users->id_client)->nama_client;
        $client         = Clients::where('nama_client','Like','%'.$nama_client.'%')->get();
        $data_karyawan  = Karyawan::where('id_karyawan',$id_karyawan)->first();
        $view           = 'layouts.akun.data_perusahaan';
        $jabatan    = Jabatan::where('id_client',$data_users->id_client)->get();
        $divisi     = Divisi::where('id_client',$data_users->id_client)->get();
        return view('layouts.akun.index',compact('data_users','view','client','jabatan','divisi','data_karyawan'));

    }
    function dokumen($hash) {
        $id_karyawan = EncryprVariable($hash);
        // $jabatan     = Jabatan::where('id_')
        $data_karyawan  = Karyawan::where('id_karyawan',$id_karyawan)->first();
        $data_users     = User::where('id_karyawan',$id_karyawan)->first();
        $view  = 'layouts.akun.data_dokumen';
        return view('layouts.akun.index',compact('data_users','view','data_karyawan'));

    }
    function bank($hash) {
        $id_karyawan    = EncryprVariable($hash);
        $bank           = Bank::all();
        $data_karyawan  = Karyawan::where('id_karyawan',$id_karyawan)->first();
        $data_users     = User::where('id_karyawan',$id_karyawan)->first();
        $view  = 'layouts.akun.data_bank';
        return view('layouts.akun.index',compact('data_users','view','data_karyawan','bank'));

    }

    function update_data(Request $request,$hash) {
        // dd($request->all());
        $type = $request->id_users != null ? 'db-user' : 'db-karyawan';

        if($type == 'db-user') {

            $find           = User::find($request->id_users);
            $find->email    = $request->email;
            $find->username = $request->username;
            if($request->password != null){
                $find->password = Hash::make($request->password);
            }
            $find->update();

            if($request->hasfile('foto')) {
                $getFilemanager     = Filemanager::where('id_karyawan',$find->id_karyawan)->where('slug','foto_profile')->first();
                $path_old           = public_path().$getFilemanager->path.$getFilemanager->filename;
                unlink($path_old);


                $filenew        = $request->foto;
                $filename       = "PP_".date("YmdHi").'.'.$filenew->getClientOriginalExtension();
                $path           = '/filemanager/photo_profile/';
                $move_folder    = $path.$filename;
                $filenew->move(public_path($path),$filename);
                $updateFile = Filemanager::find($getFilemanager->id);
                $updateFile->filename   = $filename;
                $updateFile->path       = $path;
                $updateFile->extension  = $filenew->getClientOriginalExtension();
                $updateFile->update();
            }
            $nama_karyawan = $find->name;
            Aktivitas("Melakukan pembaruan akun data Karyawan dengan nama : ".$find->name."-".$find->id_karyawan.", pada tanggal ".Carbon::now()->translatedFormat("d F Y")."  ");
        }else {
            if($request->type == 'data-diri') {

                return $this->update_data_diri($request);

            }else if($request->type == 'data-perusahaan'){

                return $this->update_data_perusahaan($request);

            }else if($request->type == 'data-dokumen') {

                return $this->update_dokumen($request);
                dd($request->type);

            }
        }

        return redirect()->route('karyawan')->with('success','Berhasil memperbarui karyawan '.$nama_karyawan.'');
    }

    function update_data_diri($request) {
        $find                   = Karyawan::find($request->id_table_karyawan);
        $find->nama_karyawan    = $request->name;
        $find->no_hp            = $request->nohp;
        $find->tempat_lahir     = $request->TmptLhr;
        $find->tanggal_lahir    = $request->tglLhr;
        $find->jenis_kelamin    = $request->jk;
        $find->alamat           = $request->alamat;
        $find->alamat_domisili  = $request->alamat_domisili;
        $find->update();

        $user = User::where('id_karyawan',$find->id_karyawan)->first();

        $update_user        = User::find($user->id);
        $update_user->name  = $request->name;
        $update_user->update();

        Aktivitas("Melakukan pembaruan data pribadi karyawan dengan nama : ".$find->nama_karyawan."-".$find->id_karyawan.", pada tanggal ".Carbon::now()->translatedFormat("d F Y")."  ");

        return redirect()->route('karyawan')->with('success','Berhasil memperbarui karyawan '.$find->nama_karyawan.'');
    }

    function update_data_perusahaan($request) {
        $find                   = Karyawan::find($request->id_table_karyawan);
        $find->lokasi_kerja     = $request->branch;
        $find->jabatan          = $request->jabatan;
        $find->divisi           = $request->divisi;
        $find->join_date        = $request->join_date;
        $find->end_date         = $request->end_date;
        $find->no_sio           = $request->noSio;
        $find->cuti             = $request->cuti;
        $find->tJabatan         = $request->tJabatan;
        $find->tTransport       = $request->tTransportasi;
        $find->gol_karyawan     = $request->gol_karyawan;
        $find->marital          = $request->marital;
        $find->update();

        $user = User::where('id_karyawan',$find->id_karyawan)->first();

        $update_user                = User::find($user->id);
        $update_user->id_client     = $request->branch;
        $update_user->update();

        Aktivitas("Melakukan pembaruan data perusahaan Karyawan dengan nama : ".$find->nama_karyawan."-".$find->id_karyawan.", pada tanggal ".Carbon::now()->translatedFormat("d F Y")."  ");


        return redirect()->route('karyawan')->with('success','Berhasil memperbarui karyawan '.$find->nama_karyawan.'');


    }

    function update_dokumen($request) {
        $id_karyawan    = Karyawan::find($request->id_table_karyawan)->id_karyawan;
        // dd($id_karyawan);
        $nama_karyawan  = Karyawan::find($request->id_table_karyawan)->nama_karyawan;
        $id             = Karyawan::find($request->id_table_karyawan)->id;

        $update             = Karyawan::find($id);
        $update->no_npwp    = $request->n_npwp;
        $update->no_jkn     = $request->n_jkn;
        $update->no_kpj     = $request->n_kpj;
        $update->nik        = $request->n_ktp;
        $update->update();


        if($request->hasFile('ktp')){
            $cek_file_manager   = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','ktp')->count();
            if($cek_file_manager != 0) {
                $getFilemanager     = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','ktp')->first();
                $path_old           = public_path().$getFilemanager->path.$getFilemanager->filename;
                unlink($path_old);


                $filenew        = $request->ktp;
                $filename       = "KTP_".date("YmdHi").'.'.$filenew->getClientOriginalExtension();
                $path           = '/filemanager/ktp/';
                $move_folder    = $path.$filename;

                $filenew->move(public_path($path),$filename);

                $updateFile = Filemanager::find($getFilemanager->id);
                $updateFile->filename   = $filename;
                $updateFile->path       = $path;
                $updateFile->extension  = $filenew->getClientOriginalExtension();
                $updateFile->update();
            }else {
                $file       = $request->ktp;
                $filename   = "KTP_".date("YmdHi").'.'.$file->getClientOriginalExtension();
                $path       = '/filemanager/ktp/';
                $move_folder= $path.$filename;

                $data_upload = [
                    'file'      => $file,
                    'filename'  => $filename,
                    'path'      => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'id_karyawan' => $id_karyawan,
                    'slug'      => 'ktp',
                    'keterangan'=> 'KTP '.$request->name
                ];

                $this->upload_file($data_upload);
            }
        }
        if($request->hasfile('jkn')){
            $cek_file_manager   = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','jkn')->count();
            if($cek_file_manager != 0) {
                $getFilemanager     = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','jkn')->first();
                $path_old           = public_path().$getFilemanager->path.$getFilemanager->filename;
                unlink($path_old);


                $filenew        = $request->jkn;
                $filename       = "jkn_".date("YmdHi").'.'.$filenew->getClientOriginalExtension();
                $path           = '/filemanager/jkn/';
                $move_folder    = $path.$filename;

                $filenew->move(public_path($path),$filename);

                $updateFile = Filemanager::find($getFilemanager->id);
                $updateFile->filename   = $filename;
                $updateFile->path       = $path;
                $updateFile->extension  = $filenew->getClientOriginalExtension();
                $updateFile->update();
            }else {
                $file       = $request->jkn;
                $filename   = "jkn_".date("YmdHi").'.'.$file->getClientOriginalExtension();
                $path       = '/filemanager/jkn/';
                $move_folder= $path.$filename;

                $data_upload = [
                    'file'      => $file,
                    'filename'  => $filename,
                    'path'      => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'id_karyawan' => $id_karyawan,
                    'slug'      => 'jkn',
                    'keterangan'=> 'jkn '.$nama_karyawan
                ];

                $this->upload_file($data_upload);
            }
        }
        if($request->hasfile('kpj')){
            $cek_file_manager   = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','kpj')->count();
            if($cek_file_manager != 0) {
                $getFilemanager     = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','kpj')->first();
                $path_old           = public_path().$getFilemanager->path.$getFilemanager->filename;
                unlink($path_old);


                $filenew        = $request->kpj;
                $filename       = "kpj_".date("YmdHi").'.'.$filenew->getClientOriginalExtension();
                $path           = '/filemanager/kpj/';
                $move_folder    = $path.$filename;

                $filenew->move(public_path($path),$filename);

                $updateFile = Filemanager::find($getFilemanager->id);
                $updateFile->filename   = $filename;
                $updateFile->path       = $path;
                $updateFile->extension  = $filenew->getClientOriginalExtension();
                $updateFile->update();
            }else {
                $file       = $request->kpj;
                $filename   = "kpj_".date("YmdHi").'.'.$file->getClientOriginalExtension();
                $path       = '/filemanager/kpj/';
                $move_folder= $path.$filename;

                $data_upload = [
                    'file'      => $file,
                    'filename'  => $filename,
                    'path'      => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'id_karyawan' => $id_karyawan,
                    'slug'      => 'kpj',
                    'keterangan'=> 'kpj '.$nama_karyawan
                ];

                $this->upload_file($data_upload);
            }
        }
        if($request->hasfile('npwp')){
            $cek_file_manager   = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','npwp')->count();
            if($cek_file_manager != 0) {
                $getFilemanager     = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','npwp')->first();
                $path_old           = public_path().$getFilemanager->path.$getFilemanager->filename;
                unlink($path_old);


                $filenew        = $request->npwp;
                $filename       = "npwp_".date("YmdHi").'.'.$filenew->getClientOriginalExtension();
                $path           = '/filemanager/npwp/';
                $move_folder    = $path.$filename;

                $filenew->move(public_path($path),$filename);

                $updateFile = Filemanager::find($getFilemanager->id);
                $updateFile->filename   = $filename;
                $updateFile->path       = $path;
                $updateFile->extension  = $filenew->getClientOriginalExtension();
                $updateFile->update();
            }else {
                $file       = $request->npwp;
                $filename   = "npwp_".date("YmdHi").'.'.$file->getClientOriginalExtension();
                $path       = '/filemanager/npwp/';
                $move_folder= $path.$filename;

                $data_upload = [
                    'file'      => $file,
                    'filename'  => $filename,
                    'path'      => $path,
                    'extension' => $file->getClientOriginalExtension(),
                    'id_karyawan' => $id_karyawan,
                    'slug'      => 'npwp',
                    'keterangan'=> 'npwp '.$nama_karyawan
                ];

                $this->upload_file($data_upload);
            }
        }
        Aktivitas("Melakukan pembaruan data dokumen karyawan dengan nama : ".$nama_karyawan."-".$nama_karyawan.", pada tanggal ".Carbon::now()->translatedFormat("d F Y, H:I:s")."  ");

        return redirect()->route('karyawan')->with('success','Berhasil memperbarui dokumen karyawan '.$nama_karyawan.'');

    }

    function view_file(Request $request, $hash) {
        $getFile = Filemanager::where('id_karyawan',$request->id_karyawan)->where("slug",$request->tipe_file)->count();

        if($getFile != 0) {
            $data = Filemanager::where('id_karyawan',$request->id_karyawan)->where("slug",$request->tipe_file)->first();

            $res = [
                'status'    => TRUE,
                'alt'       => $data->keterangan,
                'path'      => asset($data->path),
                'extension' => $data->extension,
            ];
        }else {
            $res = ['status' => FALSE, 'title' => 'File tidak tersedia' , 'pesan' => 'Upload file '.$request->tipe_file.' terlebih dahulu'];
        }
        return response()->json($res);
    }

}

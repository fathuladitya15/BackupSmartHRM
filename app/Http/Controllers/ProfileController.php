<?php

namespace App\Http\Controllers;

use Str;
use Auth;
use Validator;

use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\Models\Filemanager;
use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\Clients;
use App\Models\User;
use App\Models\Divisi;
use Carbon\Carbon;

class ProfileController extends Controller
{
    function __construct() {
        $this->middleware("auth");
    }

    function index($menu) {


        $role       = Auth::user()->roles;
        $kr         = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first();
        $jabatan    = Jabatan::find($kr->jabatan)->nama_jabatan;
        $divisi     = Divisi::find($kr->divisi)->nama_divisi;
        $join_date  = Carbon::parse($kr->join_date)->translatedFormat('l, d F Y');
        $avatar     = $kr->jenis_kelamin == "L" ? 1 : 6;
        $lokasi_kerja = Clients::find($kr->lokasi_kerja);


        $pp_cek  = Filemanager::where("slug",'foto_profile')->where('id_karyawan', Auth::user()->id_karyawan)->first();

        if($pp_cek) {
            $foto_profile = asset($pp_cek->path) ;
        }else {
            $foto_profile = asset('assets/img/avatars/'.$avatar.'.png');
        }

        if($menu == 'akun') {
            $v = 'layouts.profile.pAkun';
        }else if($menu == 'dokumen') {
            $v = 'layouts.profile.pDokumen';
        }else if($menu == 'datadiri'){
            $v = 'layouts.profile.pDatadiri';
        }else if($menu == 'karyawan') {
            $v = 'layouts.profile.pKaryawan';
        }else {
            abort(404);
        }

        return view("layouts.profile.vBaseProfile",compact('jabatan','divisi','join_date','kr','foto_profile','v','lokasi_kerja'));


        // if(in_array($role,['kr-pusat','kr-project','spv-internal','hrd','direktur','admin'])){
        //     return view("layouts.profile.vBaseProfile",compact('jabatan','divisi','join_date','kr','foto_profile'));
        // }else {
        //     dd($role);
        // }

    }

    function update_password(Request $request) {
        $pass = $request->password;
        $c_pass = $request->password_confirmation;
        $regex  = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/';


        if($request->password) {
            $rule = [
                'password' => 'confirmed|min:8',
                'password_confirmation' => '',
                'profile_pictures' => 'mimes:jpg,jpeg,png|max:1000',
            ];
            $message = [
                // 'password.required' => 'Password Harus diisi',
                'password.min'      => 'Password minimal 8 karakter',
                'password.confirmed' => 'Password Tidak sama dengan Konfirmasi Password',
                // 'password_confirmation.required' => 'Konfirmasi Password Harus diisi',
                'profile_pictures.mimes' => 'Jenis File yang diizinkan JPG,JPEG,PNG',
                'profile_pictures.max' => 'Foto Profile Ukuran Maksimal 1 Mb',

            ];
            $validasi = Validator::make($request->all(),$rule,$message);
            if($validasi->fails()){
                return response()->json($validasi->errors()->all());
            }
            $update_pw = User::find(Auth::user()->id);
            $update_pw->password = Hash::make($request->password);
            $update_pw->update();
        }


        if($request->hasFile('profile_pictures')){


            $file       = $request->profile_pictures;
            $slug       = 'foto_profile';
            $filename   = 'PP_'.date('Ymdhi').'.'.$file->getClientOriginalExtension();
            $path       = '/filemanager/photo_profile/';

            $getFilemanager = Filemanager::where("slug",'foto_profile')->where('id_karyawan',Auth::user()->id_karyawan);
            $cek = $getFilemanager->count();
            if($cek > 0){
                // $pesan = "UPDATAE";
                $path_old           = public_path().$getFilemanager->first()->path;

                unlink($path_old);

                $file->move(public_path($path),$filename);
                $update_pp = Filemanager::find($getFilemanager->first()->id);
                $update_pp->filename = $filename;
                $update_pp->path = $path.$filename;
                $update_pp->update();
            }else {
                // $pesan = "CERATE";

                Filemanager::create([
                    'filename'  => $filename,
                    'path'      => $path.$filename,
                    'extension' => $file->getClientOriginalExtension(),
                    'id_karyawan' => Auth::user()->id_karyawan,
                    'slug'      => 'foto_profile',
                    'keterangan' => 'File foto_profile '.Auth::user()->name,
                ]);
                $file->move(public_path($path),$filename);

            }
        }



        $pesan = ['status' => TRUE, 'title' => 'Sukses' ,'pesan' => 'Data berhasil diperbarui','data' => $request->all()];
        return response()->json($pesan);
    }



    function upload_files(Request $request) {
        $cek_files = Filemanager::where('id_karyawan',$request->id_karyawan)->where('slug',$request->jenis_file)->count();


        $file       = $request->file;
        $slug       = Str::upper($request->jenis_file);
        $filename   = $slug.'_'.date('Ymdhi').'.'.$file->getClientOriginalExtension();
        $path       = '/filemanager/'.$request->jenis_file.'/';


        if($cek_files > 0) {
            $getFilemanager     = Filemanager::where('id_karyawan',Auth::user()->id_karyawan)->where('slug',$request->jenis_file)->first();
            if($request->id_filemanager) {
                $path_old           = public_path().$getFilemanager->path;

                unlink($path_old);
                $file->move(public_path($path),$filename);

                $data_u = Filemanager::find($request->id_filemanager);
                $data_u->filename = $filename;
                $data_u->path = $path.$filename;
                $data_u->update();

                $pesan = ['status' => TRUE,'title' => 'Data Sudah diperbarui', 'pesan' => 'File '.$request->jenis_file.' berhasil diperbarui','data' => 0];
            }else {

                $pesan = ['status' => TRUE,'title' => 'Data Sudah tersedia', 'pesan' => "apakah anda ingin mengupdate data ".$request->jenis_file."",'id_filemanager' => $getFilemanager->id];
            }
        }else {
            $file->move(public_path($path),$filename);
            Filemanager::create([
                'filename'  => $filename,
                'path'      => $path.$filename,
                'extension' => $file->getClientOriginalExtension(),
                'id_karyawan' => Auth::user()->id_karyawan,
                'slug'      => $request->jenis_file,
                'keterangan' => 'File '.$request->jenis_file.' '.Auth::user()->name,
            ]);
            $pesan =['status' => TRUE,'title'=> 'Sukses' ,'pesan' => 'dokumen berhasil ditambahkan' ,'data' => 0];
        }
        return response()->json($pesan);
    }
}

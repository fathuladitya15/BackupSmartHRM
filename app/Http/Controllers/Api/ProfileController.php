<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Clients;
use App\Models\User;
use App\Models\Filemanager;
use Carbon\Carbon;
use Str;

class ProfileController extends Controller
{
    function index(Request $request)  {
        // return response()->json($request->all());
        $id_karyawan = $request->id_karyawan;

        $data_karyawan = Karyawan::where('id_karyawan',$id_karyawan)->first();
        $data_users    = User::where('id_karyawan',$id_karyawan)->first();
        if($data_karyawan) {
            $divisi        = Divisi::find($data_karyawan->divisi)->nama_divisi;
            $jabatan       = Jabatan::find($data_karyawan->jabatan)->nama_jabatan;
            $client        = Clients::find($data_karyawan->lokasi_kerja)->nama_client;
            $pp_cek        = Filemanager::where("slug",'foto_profile')->where('id_karyawan', $id_karyawan)->first();
            $npwp          = Filemanager::where("slug",'npwp')->where('id_karyawan', $id_karyawan)->first();
            $kpj           = Filemanager::where("slug",'kpj')->where('id_karyawan', $id_karyawan)->first();
            $ktp           = Filemanager::where("slug",'ktp')->where('id_karyawan', $id_karyawan)->first();
            $jkn           = Filemanager::where("slug",'jkn')->where('id_karyawan', $id_karyawan)->first();
            $lain          = Filemanager::where("slug",'lainnya')->where('id_karyawan', $id_karyawan)->first();
            $cv            = Filemanager::where("slug",'cv')->where('id_karyawan', $id_karyawan)->first();

            if(in_array($data_users->roles,['kr-project','kr-pusat'])) {
                $rol = Str::title('Karyawan');
            }else {
                $rol = Str::title($data_users->roles);
            }
            $avatar        = $data_karyawan->jenis_kelamin == "L" ? 1 : 6;
            if($pp_cek) {
                $foto_profile = asset($pp_cek->path.$pp_cek->filename) ;
            }else {
                $foto_profile = asset('assets/img/avatars/'.$avatar.'.png');
            }
    
            $data = [
                'foto_profile'          => $foto_profile,
                'status_akun'           => $data_karyawan->status, 
                'nama_karyawan'         => $data_karyawan->nama_karyawan,
                'no_hp'                 => $data_karyawan->no_hp,
                'tempat_lahir'          => $data_karyawan->tempat_lahir,
                'tanggal_lahir'         => $data_karyawan->tanggal_lahir,
                'jenis_kelamin'         => $data_karyawan->jenis_kelamin,
                // 'tipe_akun'             =>
                'email'                 => $data_users->email,
                'alamat'                => $data_karyawan->alamat,
                'alamat_domisili'       => $data_karyawan->alamat_domisili,
                'nama_ibu'              => $data_karyawan->nama_ibu,
                'no_kk'                 => $data_karyawan->no_kk,
                'pendidikan'            => $data_karyawan->pendidikan,
                'usia'                  => $data_karyawan->usia,
                'agama'                 => $data_karyawan->agama,
                'cuti'                  => "",
                'nama_anak'             => $data_karyawan->nama_anak,
                'nama_suami_istri'      => $data_karyawan->nama_s_i,
                'nama_bapa'             => $data_karyawan->nama_bapa,
                'jumlah_anak'           => $data_karyawan->jmlh_anak,


                'id_karyawan'           => $data_karyawan->id_karyawan,
                'id_client'             => $data_karyawan->lokasi_kerja,
                'lokasi_kerja'          => $client,
                'nama_client'           => $client,
                'divisi'                => $divisi,
                'divisi_id'             => $data_karyawan->divisi,
                'jabatan_id'            => $data_karyawan->jabatan,
                'jabatan'               => $jabatan,
                'join_date'             => Carbon::parse($data_karyawan->join_date)->translatedFormat('d F Y'),
                'end_date'              => Carbon::parse($data_karyawan->end_date)->translatedFormat('d F Y'),
                'no_sio'                => $data_karyawan->no_sio,
                'tTransport'            => $data_karyawan->tTransport,
                'tJabatan'              => $data_karyawan->tJabatan,
                'gol_karyawan'          => $data_karyawan->gol_karyawan,
                'marital'               => $data_karyawan->marital,

                'nik'                   => $data_karyawan->nik,
                'nomor_npwp'            => $data_karyawan->no_npwp,
                'nomor_kpj'             => $data_karyawan->no_kpj,
                'nomor_jkn'             => $data_karyawan->no_jkn,

                'file_npwp'             => $npwp    == null ? "" : asset($npwp->path.$npwp->filename),
                'file_ktp'              => $ktp     == null ? "" : asset($ktp->path.$ktp->filename),
                'file_jkn'              => $jkn     == null ? "" : asset($jkn->path.$jkn->filename),
                'file_kpj'              => $kpj     == null ? "" : asset($kpj->path.$kpj->filename),
                'file_lain'             => $lain    == null ? "" : asset($lain->path.$lain->filename),
                'file_cv'               => $cv      == null ? "" : asset($cv->path.$cv->filename),

                'roles'                 =>  $rol,
                'latitude_user'         => -6.242987898115869,
                'longitude_user'        => 106.8450629952746
            ];
    
            return response()->json($data);
        }else {
            return response()->json(['status' => 404,'pesan' => 'Data tidak tersedia.']);
        }
        
    }
}

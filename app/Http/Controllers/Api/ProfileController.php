<?php

namespace App\Http\Controllers\Api;


use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Clients;
use App\Models\Cuti;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Filemanager;
use Carbon\Carbon;
use Str;
use DB;

class ProfileController extends Controller
{
    function index(Request $request)  {
        $id_karyawan        = $request->id_karyawan;
        $currentMonth       = Carbon::now()->format('m');
        $data_karyawan      = Karyawan::where('id_karyawan',$id_karyawan)->first();
        $data_users         = User::where('id_karyawan',$id_karyawan)->first();
        $tahun_ini          = Carbon::now()->format('Y');
        $total_semua_cuti   = DB::table('table_log_cuti')->where('id_karyawan',$id_karyawan)
                                ->where('tahun',$tahun_ini)
                                ->sum('cuti_get');
        $sisa_cuti          = 12 -  $total_semua_cuti;
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
            $roles         = $data_users->roles;

            if(in_array($data_users->roles,['kr-project','kr-pusat'])) {
                $idLogins       = 1;
                $StatusLogin    = 'Karyawan Internal PFI';
                $rol            = Str::title('Karyawan');
            }else {
                $StatusLogin    = 'Karyawan External PFI';
                $idLogins       = 0;
                $rol            = Str::title($data_users->roles);
            }
            $avatar        = $data_karyawan->jenis_kelamin == "L" ? 1 : 6;
            if($pp_cek) {
                $foto_profile = asset($pp_cek->path.$pp_cek->filename) ;
            }else {
                $foto_profile = asset('assets/img/avatars/'.$avatar.'.png');
            }
    
            $data = [
                'foto_profile'          => foto_profile($id_karyawan),
                'status_akun'           => $data_karyawan->status, 
                'nama_karyawan'         => $data_karyawan->nama_karyawan,
                'no_hp'                 => $data_karyawan->no_hp,
                'tempat_lahir'          => $data_karyawan->tempat_lahir,
                'tanggal_lahir'         => $data_karyawan->tanggal_lahir,
                'jenis_kelamin'         => $data_karyawan->jenis_kelamin,

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
                'kategori'              => $data_karyawan->kategori == 'pusat' ? "Karyawan Pusat (Kantor PT.PFI)" : "Karyawan Internal Pusat (Project)",
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

                'nama_bank'             => $data_karyawan->nama_bank,
                'cabang_bank'           => $data_karyawan->cabang_bank,
                'kode_bank'             => $data_karyawan->kd_bank,
                'norek_bank'            => $data_karyawan->norek_bank,

                'nik'                   => $data_karyawan->nik,
                'nomor_npwp'            => $data_karyawan->no_npwp,
                'nomor_kpj'             => $data_karyawan->no_kpj,
                'nomor_jkn'             => $data_karyawan->no_jkn,

                'file_npwp'             => $npwp    == null ? NULL : asset($npwp->path),
                'file_ktp'              => $ktp     == null ? NULL : asset($ktp->path),
                'file_jkn'              => $jkn     == null ? NULL : asset($jkn->path),
                'file_kpj'              => $kpj     == null ? NULL : asset($kpj->path),
                'file_lain'             => $lain    == null ? NULL : asset($lain->path),
                'file_cv'               => $cv      == null ? NULL : asset($cv->path),

                'roles'                 =>  $rol,
                'latitude_user'         => -6.242987898115869,
                'longitude_user'        => 106.8450629952746,
                'idLogins'              => $idLogins,
                'statusLogin'           => $StatusLogin,
                'sisa_cuti'             => $sisa_cuti
            ];
    
            return response()->json($data);
        }else {
            return response()->json(['status' => 404,'pesan' => 'Data tidak tersedia.']);
        }
        
    }

    public function updatePassword(Request $request)
    {
        $passwordPattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^\w\d\s])[\w\d\s]{8,}$/';
        $validator = Validator::make($request->all(), [
            'id_karyawan'   => ['required'],
            'password'      => ['required','string', 'min:8',
            // 'regex:'.$passwordPattern,
            // must be at least 10 characters in length
            'regex:/[a-z]/',      // must contain at least one lowercase letter
            'regex:/[A-Z]/',      // must contain at least one uppercase letter
            'regex:/[0-9]/',      // must contain at least one digit
            'regex:/[@$!%*#?&.-]/', // must contain a special character

        ],
        ], [
            'id_karyawan.required'      => 'ID Karyawan dibutuhkan',
            'password.required'         => 'Bidang kata sandi wajib diisi.',
            'password.string'           => 'Bidang kata sandi harus berupa string.',
            'password.min'              => 'Password minimal 8 karakter',
            'password.regex'            => 'Password harus mengandung setidaknya satu huruf kapital, satu huruf non-kapital, satu angka, dan satu karakter khusus.',
        ]);

        if ($validator->fails()) {
            return response()->json(['pesan' => $validator->errors()->first()], 422);
        }

        // Proses pembaruan password jika validasi berhasil
        
        $getUser        = User::where('id_karyawan',$request->id_karyawan)->first();

        if($getUser) {
            $getUser->password  =   Hash::make($request->password);
            $getUser->update();
            
            return response()->json(['message' => 'Password berhasil diperbarui'], 200);
        }
        
        return response()->json(['pesan' => 'Data tidak ditemukan'],404);
    }
}

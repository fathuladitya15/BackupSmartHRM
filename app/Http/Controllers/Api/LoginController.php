<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Str;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Clients;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Login MOBILE
     */
    public function login(Request $request)
    {

        // VALIDASI
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required'
        ],[
            'username.required'     => 'Username wajib diisi.',
            'password.required'     => 'Password wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json(['pesan' => $validator->errors()->first()], 422);
        }
        $queryUser  = User::where('username', $request->get('username'));
        $user       = $queryUser->first();
        $as_login   = $request->as_login;

        if($user) {
             // Cek kecocokan role dengan pilihan login_as
             if ($user->roles == 'karyawan' && $as_login != 'internal') {
                return response()->json(['pesan' => 'Akun anda tidak memiliki akses management PFI.'],422);
            }
            else if($user->roles == 'direktur' && $as_login == 'internal' ) {
                return response()->json(['pesan' => 'Akun anda tidak memiliki terdaftar sebagai karyawan.'],422);
            }
        }

        if($queryUser->count() == 0) {
            return response()->json(['pesan' => "Akun anda tidak terdaftar."],404);
        }

        $karyawan           = Karyawan::where('id_karyawan',$user->id_karyawan)->first();
        $jabatan            = $karyawan->jabatan()->first()->nama_jabatan;
        if($as_login == 'project' &&  in_array($user->roles,['kr-pusat','kr-project']) ) {
            if($jabatan == 'Supervisor') {
                if($karyawan->divisi == 4) {
                    $user->roles = 'hrd';
                    $user->save();
                }else {
                    $user->roles = 'spv-internal';
                    $user->save();
                }
            }else if($jabatan == 'Manager') {
                $user->roles = 'manajer';
                $user->save();
            }
        }
        else if($user->roles == 'spv-internal' && $as_login == 'internal') { // SPV Login Karyawan PFI
            $user->roles = 'kr-project';
            $user->save();
        }
        else if($user->roles == 'hrd' && $as_login == 'internal') { // HRD Login Karyawan PFI
            $user->roles = 'kr-pusat';
            $user->save();
        }
        else if($user->roles == 'manajer' && $as_login == 'internal') { // Manager Login Karyawan PFI
            $user->roles = 'kr-pusat';
            $user->save();
        }

        $loginData = ['username' => $request->username,'password' => $request->password];

        $login = Auth::Attempt($loginData);

        if ($login) {
            $user = Auth::user();
            $user->token = $this->generateToken($user->id);
            $user->save();
            $user->makeVisible('token');

            $divisi     = Karyawan::where('id_karyawan',$user->id_karyawan)->first()->divisi()->first()->nama_divisi;

            $result = [
                'id'                => $user->id,
                'name'              => $user->name,
                'username'          => $user->username,
                'email'             => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'last_session'      => $user->last_session,
                'last_seen'         => $user->last_seen,
                'roles'             => $user->roles,
                'divisi'            => $divisi,
                'id_client'         => $user->id_client,
                'id_karyawan'       => $user->id_karyawan,
                'token'             => $user->token,
                'created_at'        => $user->created_at,
                'updated_at'        => $user->updated_at,


            ];
            return response()->json([
                'pesan'       => 'Login Berhasil',
                'conntent'      => $result,
            ],200);
        }else{
            return response()->json([
                'pesan' => 'Username atau Password Tidak sesuai.'
            ],404);
        }
    }


    private function generateToken($userId)
    {
        return hash('sha256', Str::random(40) . $userId);
    }
}

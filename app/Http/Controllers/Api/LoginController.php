<?php

namespace App\Http\Controllers\Api;

use Str;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Clients;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Login MOBILE
     */
    public function login(Request $request)
    {
        $as_login           = $request->as_login;
        $cekIdKaryawan      = User::where('username', $request->get('username'))->count();

        if($cekIdKaryawan > 0) {
            $dataKaryawan   = User::where('username', $request->get('username'))->first();
            $getRoles       = $dataKaryawan->roles;
            if($as_login == 'internal') {
                if(in_array($getRoles,['kr-pusat','kr-project','karyawan'])){
                    $logedIn = 'Logged In';
                }else {
                    $logedIn = 'Akun anda tidak terdaftar sebagai karyawan';
                    return response()->json(['response_code' => 401,'pesan' => $logedIn],401);
                }
            }
            if($as_login == 'project') {
                if(in_array($getRoles,['kr-pusat','kr-project','karyawan'])){
                    $logedIn = 'Akun anda tidak terdaftar sebagai management PFI';
                    return response()->json(['response_code' => 401,'pesan' => $logedIn],401);
                }else {
                    $logedIn = 'Logged In';
                }
            }
        }else {
            $dataKaryawan   = 'Akun anda tidak terdaftar';
            $getRoles       = '';
            $logedIn        = '';
            return response()->json(['response_code' => 404,'pesan' => $dataKaryawan],404);
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
                'response_code' => 200,
                'pesan'       => 'Login Berhasil',
                'conntent'      => $result,
            ],200);
        }else{
            return response()->json([
                'response_code' => 404,
                'pesan' => 'Username atau Password Tidak sesuai!'
            ],404);
        }
    }

    private function generateToken($userId)
    {
        return hash('sha256', Str::random(40) . $userId);
    }
}

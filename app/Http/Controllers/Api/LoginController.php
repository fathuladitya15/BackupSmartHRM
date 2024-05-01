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
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        // return response()->json(['data'=> $credentials]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AppName')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // $as_login           = $request->as_login;
        // $cekIdKaryawan      = User::where('username', $request->get('username'))->count();


        // if($cekIdKaryawan > 0) {
        //     $datauser           = User::where('username', $request->get('username'));
        //     $dataKaryawan       = Karyawan::where('id_karyawan',$datauser->first()->id_karyawan)->first();
        //     $divisi             = $dataKaryawan->divisi()->first()->nama_divisi;
        //     $jabatan            = $dataKaryawan->jabatan()->first()->nama_jabatan;
        //     $userLoginsRoles    = $datauser->first()->roles;
        //     $namaClient         = Clients::find($dataKaryawan->lokasi_kerja);
        //     $loginType          = $request->get('roles');

        //     if($userLoginsRoles == 'superadmin') {
        //         return response()->json(['pesan' => 'Superadmin tidak bisa login disini'],401);
        //     }

        //     if($as_login == 'internal') {
        //         $r = [
        //             'pesan'     =>'Jabatan Anda adalah '.$jabatan. ' pada divisi '.$divisi.' di '.$namaClient->nama_client,
        //             'divisi'    => $divisi,
        //             'jabatan'   => $jabatan,
        //             'Roles'     => $userLoginsRoles
        //         ];
        //         if(in_array(!$dataKaryawan->kategori, ['karyawan'])) {
        //             $r = ['pesan' => 'Akun anda tidak terdaftar dalam manajemen PT PFI'];
        //         }else {
        //             if($userLoginsRoles == 'spv-internal') {
        //                 $updateRoles = 'kr-pusat';
        //                 $updates     = User::find($datauser->first()->id);
        //                 $updates->roles = $updateRoles;
        //                 $updates->update();
        //                 array_push($r,['Login Sebagai' => $updateRoles ]);
        //             }else if(in_array($userLoginsRoles,['admin','korlap'])){
        //                 $updateRoles  = 'kr-project';
        //                 $updates     = User::find($datauser->first()->id);
        //                 $updates->roles = $updateRoles;
        //                 $updates->update();
        //                 array_push($r,['Login Sebagai' => $updateRoles ]);
        //             }
        //         }
        //     }else if($as_login == 'project') {
        //         $r = ['roles' => $userLoginsRoles,'id_client' => $dataKaryawan->lokasi_kerja];
        //         if($dataKaryawan->lokasi_kerja != 1) {
        //             if($jabatan == 'Supervisor') {
        //                 if($userLoginsRoles != 'hrd') {
        //                     $updateRoles = 'spv-internal';
        //                     $updates     = User::find($datauser->first()->id);
        //                     $updates->roles = $updateRoles;
        //                     $updates->update();
        //                     array_push($r,['update Data' => $updateRoles]);
        //                 }else {
        //                     $r = ['akun anda' => $userLoginsRoles];
        //                 }
        //             }else if($jabatan == 'Admin') {
        //                 $updateRoles = 'admin';
        //                 $updates     = User::find($datauser->first()->id);
        //                 $updates->roles = $updateRoles;
        //                 $updates->update();
        //                 array_push($r,['update Data' => $updateRoles]);

        //             }else if($jabatan == 'Koordinator Lapangan') {
        //                 $updateRoles = 'korlap';
        //                 $updates     = User::find($datauser->first()->id);
        //                 $updates->roles = $updateRoles;
        //                 $updates->update();
        //                 array_push($r,['update Data' => $updateRoles]);
        //             }
        //         }else {
        //             if(in_array($jabatan,['Direktur','Manager','Head'])){
        //                 $r = ['pesan' => 'Akun anda tidak terdatar disini'];
        //             }
        //         }

        //     }
        //     $loginData = ['username' => $request->username,'password' => $request->password];
        //     $updateData = User::find($datauser->first()->id);
        //     return response()->json(['data' => $r,'roles' => $updateData->roles],200);
        // }else {
        //     return response()->json(['status' => 200,'pesan' => 'Akun anda tidak terdaftar']);
        // }



        // $login = Auth::Attempt($loginData);

        // if ($login) {
        //     $user = Auth::user();
        //     $user->token = $this->generateToken($user->id);
        //     $user->save();
        //     $user->makeVisible('token');

        //     return response()->json([
        //         'response_code' => 200,
        //         'message'       => 'Login Berhasil',
        //         // 'as_login'      =>
        //         'conntent'      => $user
        //     ]);
        // }else{
        //     return response()->json([
        //         'response_code' => 404,
        //         'message' => 'Username atau Password Tidak Ditemukan!'
        //     ]);
        // }
    }
    private function generateToken($userId)
    {
        return hash('sha256', Str::random(40) . $userId);
    }
}

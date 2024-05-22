<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pengumuman;
use App\Models\Filemanager;
use DB;
use Carbon\Carbon;

class PengumumanController extends Controller
{
    function getPengumumanAdmin(Request $request) {

        $id_karyawan = $request->id_karyawan;
        if($id_karyawan  == null || $id_karyawan  == "") {
            return response()->json(['pesan' => "ID Karyawan dibutuhkan."],422);
        }

        $cekID = User::where('id_karyawan',$id_karyawan);

        if($cekID->count() == 0){
            return response()->json(['pesan' => "ID Karyawan tidak terdaftar."],404);
        }

        $dataUser = $cekID->first();

        if(!in_array($dataUser->roles,['admin','korlap'])) {
            return response()->json(['pesan' => 'Anda tidak memiliki akses.'],401);
        }

        $data =DB::table('table_pengumuman')
            ->where('id_client',$dataUser->id_client)
            ->where('arsip',0)
            ->where('views',1)
            ->orwhere('views',3)
            ->orderBy('created_at', 'desc')->get();

        $result = [];
        foreach ($data as $key ) {
            $result[] = [
                'id'        => $key->id,
                'judul'     => $key->judul,
                'tanggal'   => Carbon::parse($key->created_at)->translatedFormat('l, d F Y'),
                'pembuat'   => $dataUser->name,
                'detail'    => $this->detailPengumuman($key->id)
            ];
        }

        return response()->json(['data' => $result]);
    }

    function detailPengumuman($id) {
        $data = DB::table('table_pengumuman')->where('id',$id)->first();
        $queryLampiran = Filemanager::where('id_karyawan',$id)->where('slug','lampiran');
        if($queryLampiran->count() == 0) {
            $lampiran = "";
        }else {
            $lampiran = asset($queryLampiran->first()->path);
        }

        $userCreated = User::where('id_karyawan',$data->pembuat)->first();
        $result = [
            'judul'     => $data->judul,
            'tanggal'   => Carbon::parse($data->created_at)->translatedFormat('l, d F Y'),
            'pembuat'   => $userCreated->name,
            'lampiran'  => $lampiran
        ];

        return $result;
    }

}

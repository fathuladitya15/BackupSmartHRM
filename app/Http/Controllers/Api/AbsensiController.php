<?php

namespace App\Http\Controllers\Api;

use DB;
use File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\Filemanager;
use App\Models\Clients;
use App\Models\User;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    function get_data_absensi(Request $request) {
        $yearsNow       =   Carbon::now()->format('Y');
        $mothNow        =   Carbon::now()->format('m');
        $dateNow        =   $yearsNow.'-'.$mothNow.'-01';
        $dateLastMonth  =   Carbon::parse($dateNow)->endOfMonth()->format('Y-m-d'); // MENGAMBIL TOTAL HARI DALAM BULAN SEKARANG

        $data           =   Absensi::whereBetween('tanggal',[$dateNow,$dateLastMonth])->where('id_karyawan',$request->id_karyawan)->orderBy('created_at','DESC')->get();
        $result = [];
        foreach ($data as $key) {
            $result[] = [
                'id'                                => $key['id'],
                'id_karyawan'                       => $key['id_karyawan'],
                'nama_karyawan'                     => $key['nama_karyawan'],
                'tanggal'                           => Carbon::parse($key['tanggal'])->translatedFormat('d F Y'),
                'jam_masuk'                         => $key['jam_masuk'],
                'lokasi_absen_masuk'                => $key['lokasi_absen_masuk'],
                'detail_lokasi_absen_masuk'         => $key['detail_lokasi_absen_masuk'],
                'jam_keluar'                        => $key['jam_keluar'],
                'lokasi_absen_plg'                  => $key['lokasi_absen_plg'],
                'detail_lokasi_absen_plg'           => $key['detail_lokasi_absen_plg'],
                'shift'                             => 'Non Shift',
                'catatan'                           => $key['catatan'],
            ];
        }
        return response()->json(['data' => $result],200);
    }

    function create_absensi(Request $request) {


        $dateNow    =   Carbon::now()->format('Y-m-d');


        $attendance = Absensi::where('id_karyawan',$request->id_karyawan)->where('tanggal',$dateNow)->count();

        if($attendance > 1) {
            return response()->json(['pesan' => 'Anda sudah absen hari ini.'],422);
        }else {
            $absen_masuk = [
                'id_karyawan'               => $request->id_karyawan,
                'nama_karyawan'             => $request->nama_karyawan,
                'divisi'                    => $request->divisi,
                'jabatan'                   => $request->jabatan,
                'tanggal'                   => $request->tanggal_absensi,
                'jam_masuk'                 => $request->jam_masuk,
                'lokasi_absen_masuk'        => $request->lokasi_absen_masuk,
                'detail_lokasi_absen_masuk' => $request->detail_lokasi_absen_masuk,
                'shift'                     => 'Non Shift',
                'catatan'                   => $request->catatan,
                'id_client'                 => 1,
            ];
            // return response()->json(['pesan' => $absen_masuk]);

            Absensi::create($absen_masuk);
            return response()->json(['pesan' => 'Absen berhasil'],200);
        }
    }

    function get_data_client(Request $request) {
        $data = collect([
            [
                'id_client'     => 1,
                'nama_client'   => 'Alamanda Tower',
                'latitude'      => -6.290953065239098,
                'longitude'     => 106.80458863760427,
            ],[
                'id_client'     => 10,
                'nama_client'   => 'Soho Pancoran',
                'latitude'      => -6.242987898115869,
                'longitude'     => 106.8450629952746,
            ]]);

        return response()->json(['data' => $data],200);
    }

    function update_absensi(Request $request) {
        $CekAbsensiPulang =  Absensi::where('tanggal',$request->tanggal_absensi)->where('id_karyawan',$request->id_karyawan)->first();
        if($CekAbsensiPulang->jam_keluar != null ) {
                return response()->json(['pesan' => 'Anda telah absen pulang'],401);
        }
        $update                          = Absensi::find($CekAbsensiPulang->id);
        $update->jam_keluar              = $request->jam_keluar;
        $update->lokasi_absen_plg        = $request->lokasi_absen_plg;
        $update->detail_lokasi_absen_plg = $request->detail_lokasi_absen_plg;
        $update->update();
        return response()->json(['pesan' => 'Anda telah absen pulang','data' => $request->all() ],200);
    }

    function get_lokasi_absen($id_client) {
        $data = Clients::find($id_client);

        $result = [
            'nama_lokasi_absen' => $data->nama_client
        ];

        return $data->nama_client;

    }

    function cek_absensi(Request  $request) {
        $dateNow        =   Carbon::now()->format('Y-m-d');
        $dateInput      =   $request->tanggal;

        if($dateInput  != $dateNow) {
            $r = 'Create Data Absensi Baru';
        }else {
            $getJamKeluar = Absensi::where('tanggal',$request->tanggal)->where('id_karyawan',$request->id_karyawan)->first();
            if($getJamKeluar == "") {
                $r = 'Belum absen pulang';
            }else {
                $r = 'Sudah absen pulang';
            }

        }


        return response()->json(['data' => $r ,'jam_keluar' => ""],200);
        // $get    = Absensi::where('tanggal',$request->tanggal)->where('id_karyawan',$request->id_karyawan)->first();

        // if($get->jam_keluar == "") {
        //     $p = 'Anda belum absen keluar';
        // }else {
        //     $p = 'Anda sudah absen keluar';
        // }
        // return response()->json(['data' => $p,'jam_keluar' => $get->jam_keluar],200);
    }

    function ceking_id_karyawan($id) {
        $cek = User::where('id_karyawan',$id)->count();
        if($cek == 0) {
            return FALSE;
        }

        return TRUE;
    }

    function absensi(Request $request) {
        $GetData = Absensi::where('id_karyawan',$request->id_karyawan)->orderBy('created_at')->first();

        return response()->json(['data' => $GetData == NULL ? "" : $GetData],200);
    }

    function filterData(Request $request) {

        $id_karyawan = $request->id_karyawan;

        if($id_karyawan == null || $id_karyawan == ""){
            return response()->json(['pesan' => 'ID Karyawan dibutuhkan'],422);

        }

        $from_date  = $request->from_date;
        $to_date    = $request->to_date;

        $data       = Absensi::whereBetween('tanggal',[$from_date,$to_date])->where('id_karyawan',$request->id_karyawan)->orderBy('created_at','DESC')->get();
        $result = [];
        foreach ($data as $key) {
            $result[] = [
                'id'                                => $key['id'],
                'id_karyawan'                       => $key['id_karyawan'],
                'nama_karyawan'                     => $key['nama_karyawan'],
                'tanggal'                           => Carbon::parse($key['tanggal'])->translatedFormat('d F Y'),
                'jam_masuk'                         => $key['jam_masuk'],
                'lokasi_absen_masuk'                => $key['lokasi_absen_masuk'],
                'detail_lokasi_absen_masuk'         => $key['detail_lokasi_absen_masuk'],
                'jam_keluar'                        => $key['jam_keluar'],
                'lokasi_absen_plg'                  => $key['lokasi_absen_plg'],
                'detail_lokasi_absen_plg'           => $key['detail_lokasi_absen_plg'],
                'shift'                             => 'Non Shift',
                'catatan'                           => $key['catatan'],
            ];
        }
        return response()->json(['data' => $result],200);
    }

    function data_absensi_hrd(Request $request){
        if($request->id_karyawan == "" || $request->id_karyawan == null) {
            return response()->json(['pesan' => 'ID Karyawan dibutuhkan'],401);
        }
        $id_karyawan = $request->id_karyawan;
        $dataUser    = User::where('id_karyawan',$id_karyawan)->first();

        if($dataUser->roles != 'hrd') {
            return response()->json(['pesan' => 'Anda tidak memiliki akses'],401);
        }

        $yearsNow       =   Carbon::now()->format('Y');
        $mothNow        =   Carbon::now()->format('m');
        $dateNow        =   $yearsNow.'-'.$mothNow.'-01';
        $dateLastMonth  =   Carbon::parse($dateNow)->endOfMonth()->format('Y-m-d'); // MENGAMBIL TOTAL HARI DALAM BULAN SEKARANG

        $data           =   Absensi::whereBetween('tanggal',[$dateNow,$dateLastMonth])->where('id_client',1)->orderBy('created_at','DESC')->get();
        $result = [];
        foreach ($data as $key) {
            $result[] = [
                'id'                                => $key['id'],
                'id_karyawan'                       => $key['id_karyawan'],
                'nama_karyawan'                     => $key['nama_karyawan'],
                'tanggal'                           => Carbon::parse($key['tanggal'])->translatedFormat('d F Y'),
                'jam_masuk'                         => $key['jam_masuk'],
                'lokasi_absen_masuk'                => $key['lokasi_absen_masuk'],
                'detail_lokasi_absen_masuk'         => $key['detail_lokasi_absen_masuk'],
                'jam_keluar'                        => $key['jam_keluar'],
                'lokasi_absen_plg'                  => $key['lokasi_absen_plg'],
                'detail_lokasi_absen_plg'           => $key['detail_lokasi_absen_plg'],
                'shift'                             => 'Non Shift',
                'catatan'                           => $key['catatan'],
            ];
        }
        return response()->json(['pesan' => $result]);
    }

    function get_absensi_admin_korlap($kr,Request $request) {
        $id_karyawan = $request->id_karyawan;
        if($id_karyawan == "" || $id_karyawan == null) {
            return response()->json(['pesan' => 'ID Karyawan dibutuhkan'],422);
        }
        $roles       = User::where('id_karyawan',$id_karyawan)->first()->roles;

        if(in_array($roles,['admin','korlap'])){
            $id_client = User::where('id_karyawan',$id_karyawan)->first()->id_client;
            $data  = DB::table('table_karyawan as kr')->select('ta.*')
                ->join('table_absensi as ta','ta.id_karyawan','=','kr.id_karyawan')
                ->where('kr.gol_karyawan',$kr)
                ->where('ta.id_client',$id_client)
                ->get();
                $result = [];
                foreach ($data as $key) {
                    $result[] = [
                        'id'                                => $key->id,
                        'id_karyawan'                       => $key->id_karyawan,
                        'nama_karyawan'                     => $key->nama_karyawan,
                        'tanggal'                           => Carbon::parse($key->tanggal)->translatedFormat('d F Y'),
                        'jam_masuk'                         => $key->jam_masuk,
                        'lokasi_absen_masuk'                => $key->lokasi_absen_masuk,
                        'detail_lokasi_absen_masuk'         => $key->detail_lokasi_absen_masuk,
                        'jam_keluar'                        => $key->jam_keluar,
                        'lokasi_absen_plg'                  => $key->lokasi_absen_plg,
                        'detail_lokasi_absen_plg'           => $key->detail_lokasi_absen_plg,
                        'shift'                             => $key->shift,
                        'catatan'                           => $key->catatan,
                    ];
                }
            return response()->json(['id Client' => $id_client,'data' => $result]);

        }else {
            return response()->json(['pesan' => 'Anda tidak memiliki akses'],401);
        }
    }
}

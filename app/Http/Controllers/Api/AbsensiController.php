<?php

namespace App\Http\Controllers\Api;

use Str;
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
use App\Models\RequestAttendace;
use Validator;

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

    function create_request_attendance(Request $request) {

        // TANGGAL PER HARI INI
        $dateNow        = Carbon::now()->format('Y-m-d');
        // TABLE KARYAWAN
        $dataMaster     = Karyawan::where('id_karyawan',$request->id_karyawan);
        // TABLE USER
        $dataUser       = User::where('id_karyawan',$request->id_karyawan)->first();
        // TABLE REQUEST ATTENDANCE BERDASARKAN TANGGAL SEKARANG
        $masterAttNow   = RequestAttendace::where('request_date',$dateNow)->where('id_karyawan',$request->id_karyawan);
        // TABLE REQUEST ATTENDANCE BERDASARKAN TANGGAL CLIENT
        $dataMasterAtt  = RequestAttendace::where('request_date',$request->tanggal)->where('id_karyawan ',$request->id_karyawan);
        // ID CLIENT KARYAWAN
        $id_client      = $dataUser->id_client;


        // CEK PERSAMAAN TANGGAL REQUEST DAN TANGGAL SERVER

        if($request->tanggal == $dateNow){

           $cekAttAvailable = $masterAttNow->count();

           if($cekAttAvailable == 0) {
                // BUAT REQUEST ABSENSI
                $status = TRUE;
                $this->insertDatabase($request,$status,$id_client);

                return response()->json(['pesan' => 'Absensi Hari ini '.$cekAttAvailable]);
            }else {

                // CEK DATA TERAKHIR BERDASARKAN TANGGAL REQUEST ABSENSI PER HARI INI DAN ID KARYAWAN
                $getLatest          = $dataMasterAtt->latest()->first();

                // GET STATUS TERAKHIR DARI DATA DI ATAS
                $getStatusLatest    = $getLatest->status;

                if($getStatusLatest == 0) {
                    $pesan = 'Pending';
                    $pesan .= ', Button Absen Disabled';
                }
                elseif($getStatusLatest == 1) {
                    $pesan = 'Disetujui';
                    $pesan .= ' Absen hari ini berhasil';
                }
                elseif($getStatusLatest == 2) {
                    $pesan = 'Absensi anda Ditolak';
                    $pesan .= '. Silahkan request absensi kembali';
                    // $this->insertDatabase($request,$status == FALSE);
                }
                return response()->json(['pesan' => $pesan]);
            }

        }else {
            return response()->json(['pesan' => 'Tanggal sama dengan tanggal server']);
        }


        return response()->json(['Cek absensi Hari ini' => $masterAttNow->count()]);


        if($dataMasterAtt->count() == 0 ) {
            $rules = [
                'tanggal'       => 'required',
                'jam'           => 'required',
                'id_karyawan'   => 'required',
                'lokasi_absen'  => 'required',
                'detail_lokasi' => 'required',
                'latitude'      => 'required',
                'longitude'     => 'required',
            ];

            $message = [
                'tanggal.required'      => 'Tanggal Absensi belum diisi.',
                'jam.required'          => 'Jam Absensi belum diiisi.',
                'id_karyawan.required'  => 'ID karyawan tidak diketahui',
                'lokasi_absen.required' => 'Lokasi absen tidak diketahui.',
                'detail_lokasi'         => 'Detail lokasi absen tidak diketahui',
                'latitude.required'     => 'Latitde tidak diketahui',
                'longitude.required'    => 'Longitude tidak diketahui',
            ];

            if($dataUser->roles == 'karyawan') {
                $newRules   = ['shift' => 'required'];
                $newMessage = ['shift.required' => 'Shift belum diisi.'];

                $rules      = array_merge($rules,$newRules);
                $message    = array_merge($message,$newMessage);
            }

            $validator = Validator::make($request->all(),$rules,$message);

            if($validator->fails()) {
                return response()->json(['pesan' => $validator->errors()->first()]);
            }

            // RequestAttendace::create([
            //     'id_karyawan'           => $request->id_karyawan,
            //     'request_date'          => $request->tanggal,
            //     'request_time'          => $request->jam,
            //     'lokasi_absen'          => $request->lokasi_absen,
            //     'detail_lokasi_absen'   => $request->detail_lokasi,
            //     'latitude'              => $request->latitude,
            //     'longitude'             => $request->longiutde,
            //     'shift'                 => $request->shift
            // ]);

            // $cekLokasi = $this->cekLokasi($id_client,$request->latitude,$request->longitude,$request->lokasi_absen);
            return response()->json(['Cek Lokasi' => ""]);

        }else {

            $getLatest = RequestAttendace::where('id_karyawan',$request->id_karyawan)->latest()->first();

            if($getLatest->status == 0) {
                $pesan = ['pesan' => 'Pending'];

            }else if($getLatest->status == 1) {
                $pesan = ['pesan' => 'Disetujui'];

            }else if($getLatest->status == 2) {
                // RequestAttendace::create([
                //     'id_karyawan'           => $request->id_karyawan,
                //     'request_date'          => $request->tanggal,
                //     'request_time'          => $request->jam,
                //     'lokasi_absen'          => $request->lokasi_absen,
                //     'detail_lokasi_absen'   => $request->detail_lokasi,
                //     'latitude'              => $request->latitude,
                //     'longitude'             => $request->longiutde,
                //     'shift'                 => $request->shift

                // ]);
                $pesan = ['pesan' => 'Buat Baru' ,'status' => 2];
            }
            return response()->json($pesan);
        }
    }

    function insertDatabase($request, $status,$id_client) {
        RequestAttendace::create([
            'id_karyawan'           => $request->id_karyawan,
            'request_date'          => $request->tanggal,
            'request_time'          => $request->jam,
            'lokasi_absen'          => $request->lokasi_absen,
            'detail_lokasi_absen'   => $request->detail_lokasi,
            'latitude'              => $request->latitude,
            'longitude'             => $request->longiutde,
            'shift'                 => $request->shift,
            'id_client'             => $id_client,
        ]);
        return $status;
    }

    function cekLokasi($id_client,$latitude,$longitude,$lokasi) {
        // KOORDINAT FIX PERUSAHAAN
        $clients        = Clients::find($id_client);
        $c_lat          = Str::limit($clients->latitude,10,'');
        $c_long         = Str::limit($clients->longitude,11,'');

        // KOORDINAT ABSENSI
        $address        = $lokasi;
        $lat            = Str::limit($latitude,10,'');
        $long           = Str::limit($longitude,11,'');

        // VALIDASI KOORDINAT
        $lat_from   =   $c_lat;
        $long_from  =   $c_long;

        $lat_To     =   $lat;
        $long_To    =   $long;

        // KONVERSI KOODINAT DARI PERUSAHAAN DENGAN KOORIDINAT ABSENSI
        $theta  = $long_from - $long_To;
        $dist   = sin(deg2rad($lat_from)) * sin(deg2rad($lat_To)) +  cos(deg2rad($lat_from)) * cos(deg2rad($lat_To)) * cos(deg2rad($theta));
        $dist   = acos($dist);
        $dist   = rad2deg($dist);


        // MENGUBAH DARI MILE KE KM
        $miles  = $dist * 60 * 1.1515;

        $distance = ($miles * 1.609344).' km';
        // dd($distance);


        // AMBIL 3 KARAKTER SAJA
        $decimals = Str::limit($distance,3,'');

        // KONVERSI DARI KM KE METER
        $km = $decimals;
        $meter = 1000;

        // VALIDASI METER
        $max = 0.5;
        $MeterFrom = $km * $meter;
        if($MeterFrom > $max) {
            $r          = "Anda terlalu jauh";
        }else {
            $r          = "Lokasi disetujui";
        }

        $detail     = ". Jarak anda ".$km."Km  dari lokasi yang sudah ditentukan.";
        $data = [
            'Alamat'                => $address,
            'Lat,Lon Absen'         => $lat.','.$long,
            'Lat, Lon Lokasi Kerja' => $c_lat.','.$c_long,
            'pesan'                 => $r. $detail,
        ];

        return $data;
    }
}

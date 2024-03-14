<?php

namespace App\Http\Controllers;

// use Location;
use DB;
use Str;
use Auth;
use PDF;
use App\Models\Clients;
use App\Models\Karyawan;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonPeriod;

class AbsensiController extends Controller
{
    function __contstruct() {
        $this->middleware("auth");
    }

    function index($kr) {
        $role = Auth::user()->roles;
        if($role == 'hrd'){
            $karyawan = Karyawan::where('kategori',$kr)->get();
            $absensi  = DB::table('table_karyawan as kr')->select('ta.*')
                ->join('table_absensi as ta','ta.id_karyawan','=','kr.id_karyawan')->where('kategori',$kr)->get();

            $route_data_table = route('absensi-data-ajax',['karyawan' =>$kr]);
            return view('layouts.hrd.vAbsensi',compact("route_data_table",'karyawan','kr'));
        }
        else if(in_array($role,['admin','korlap'])){
            $data = DB::table('table_karyawan as tk')->select('ta.*')
                        ->join('table_absensi as ta','ta.id_karyawan','=','tk.id_karyawan')
                        ->join('users as us ','us.id_karyawan','=','tk.id_karyawan')
                        ->where('us.id_client',Auth::user()->id_client)
                        ->where('gol_karyawan',$kr)
                        ->get();
                    // dd($data);
            $route_data_table = route('absensi-korlap',['gol_karyawan' =>$kr]);
            $karyawan = User::where('id_client',Auth::user()->id_client)->where('roles','karyawan')->get();

            return view('layouts.admin_korlap.vAbsensiMegasari',compact('data','kr','karyawan','route_data_table'));
        }
    }

    function search_by_one(Request $request) {
        $role      = Auth::user()->roles;
        $j         = $request->kr;
        $karyawan = Karyawan::where('kategori',$j)->get();

        $data_karyawan = Karyawan::where('id_karyawan',$request->id_karyawan)->first();
        $id_karyawan    = $request->id_karyawan;
        $from_date      = $request->from_date;
        $to_date        = $request->to_date;

        $cek = Absensi::whereBetween('tanggal',[$from_date,$to_date])->where('id_karyawan',$id_karyawan)->count();
        if($cek == 0) {
            $f = Carbon::parse($from_date)->translatedFormat('d F Y');
            $t = Carbon::parse($to_date)->translatedFormat('d F Y');
            $pesan = ['message' => 'Riwayat absensi '.$data_karyawan->nama_karyawan.' pada tanggal '.$f.' s/d '.$t.' tidak tersedia'];
            return redirect()->route('absensi-data',['karyawan' => $j])->withErrors($pesan);
        }


        $f_d = Carbon::parse($from_date);
        $t_d = Carbon::parse($to_date);
        $get_bulan =  $f_d->diffInMonths($t_d); // get total bulan dalam pencarian
        $get_tahun =  $f_d->diffInYears($t_d); // get total tahun dalam pencarian


        $tahun = $f_d->format('Y');
        $search         = Absensi::whereBetween('tanggal',[$from_date,$to_date])->where('id_karyawan',$id_karyawan)->get();

        $result = CarbonPeriod::create($from_date,'1 month',$to_date);

        $data = [];
        foreach ($search as $key ) {
                $tanggal = Carbon::parse($key->tanggal);
                $data[$tanggal->format('Y')][$tanggal->translatedFormat('F')][$key->tanggal] = [$key];

        }
        // dd($data);

        if(in_array($role,['admin','korlap'])){
            $karyawan = User::where('id_client',Auth::user()->id_client)->where('roles','karyawan')->get();
            return view('layouts.admin_korlap.vAbsensiSearchOne',compact('data_karyawan','from_date','to_date','search','j','karyawan'));
        }
        return view('layouts.hrd.vAbsensiSearchOne',compact('search','karyawan','from_date','to_date','j','data_karyawan','data'));
    }

    function dokumen_perorang(Request $request) {
        $karyawan = Karyawan::where('id_karyawan',$request->id_karyawan)->first();

        $id_karyawan    = $request->id_karyawan;
        $from_date      = $request->from_date;
        $to_date        = $request->to_date;


        $filename = "Riwayat Absensi ".$karyawan->nama_karyawan." - ".$karyawan->id_karyawan." periode ".$from_date."/".$to_date;

        $search         = Absensi::whereBetween('tanggal',[$from_date,$to_date])->where('id_karyawan',$id_karyawan)->get();
        $data = [];
        foreach ($search as $key ) {
                $tanggal = Carbon::parse($key->tanggal);
                $data[$tanggal->format('Y')][$tanggal->translatedFormat('F')][$key->tanggal] = [$key];

        }
        if(in_array(Auth::user()->roles, ['admin','korlap'])) {
            $pdf = PDF::loadview('layouts.pdf_view.pdfAbsensi2',['data' => $data,'from_date' => $from_date,'to_date' => $to_date,'data_kr' => $karyawan,'filename' => $filename]);

        }else {
            $pdf = PDF::loadview('layouts.pdf_view.pdfAbsensi',['data' => $data,'from_date' => $from_date,'to_date' => $to_date,'data_kr' => $karyawan,'filename' => $filename]);
        }
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream();
    }

    function cek(Request $request) {

        $ip = \Request::getClientIp(true); // Get the user's IP address
        // dd($ip);

        // Create a new cURL resource with URL
        $ch = curl_init();

        // API end URL
        curl_setopt($ch, CURLOPT_URL, "http://ipinfo.io/{$ip}/json");

        // Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute API request
        $execute = curl_exec($ch);

        // Close cURL resource
        curl_close($ch);

        // Retrieve IP data from API response
        $ipResult = json_decode($execute);

        // if (!empty($ipResult))

        // KOORDINAT FIX PERUSAHAAN
        $clients        = Clients::find(2);
        $c_lat          = Str::limit($clients->latitude,10,'');
        $c_long         = Str::limit($clients->longitude,11,'');

        // dd(app('geocoder'));


        // KOORDINAT ABSENSI
        $address        = "Soho pancoran";
        $result         = app('geocoder')->geocode($address)->get();
        $coordinates    = $result[0]->getCoordinates();
        $lat            = $coordinates->getLatitude();
        $long           = $coordinates->getLongitude();

        // dd($coordinates);

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
            $r = "Tidak dalam jangkauan";
        }else {
            $r = "Dalam Jangkauan";
        }
        dd($r);

        // Client::selectRaw("")

        // $now = Carbon::now()->translatedFormat('l, d F Y / H:i:s');
        // dd($now);
        return view('layouts.karyawan.vAbsensiPFI');
    }
}

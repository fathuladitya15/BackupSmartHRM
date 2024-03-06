<?php

namespace App\Http\Controllers;

// use Location;
use Str;
use App\Models\Clients;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    function __contstruct() {
        $this->middleware("auth");
    }

    function index(Request $request) {

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

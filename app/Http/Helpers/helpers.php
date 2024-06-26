<?php

// use Auth;
use Carbon\Carbon;
use App\Models\Aktifitas;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Filemanager;
use App\Models\Karyawan;


if (!function_exists('getIpInfo')) {
    function getIpInfo()
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        //Deep detect ip
        if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        $xml        = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . $ip);

        $country    = @$xml->geoplugin_countryName;
        $city       = @$xml->geoplugin_city;
        $area       = @$xml->geoplugin_areaCode;
        $code       = @$xml->geoplugin_countryCode;
        $long       = @$xml->geoplugin_longitude;
        $lat        = @$xml->geoplugin_latitude;

        $data['country'] = $country;
        $data['city'] = $city;
        $data['area'] = $area;
        $data['code'] = $code;
        $data['long'] = $long;
        $data['lat'] = $lat;
        $data['ip'] = request()->ip();
        $data['time'] = date('d-m-Y h:i:s A');

        return $data;

    }

}

if (!function_exists('getOsBrowser')) {
    function getOsBrowser()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $osPlatform = "Unknown OS Platform";
        $osArray = array(
            '/windows nt 10/i' => 'Windows 10',
            '/windows nt 6.3/i' => 'Windows 8.1',
            '/windows nt 6.2/i' => 'Windows 8',
            '/windows nt 6.1/i' => 'Windows 7',
            '/windows nt 6.0/i' => 'Windows Vista',
            '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
            '/windows nt 5.1/i' => 'Windows XP',
            '/windows xp/i' => 'Windows XP',
            '/windows nt 5.0/i' => 'Windows 2000',
            '/windows me/i' => 'Windows ME',
            '/win98/i' => 'Windows 98',
            '/win95/i' => 'Windows 95',
            '/win16/i' => 'Windows 3.11',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/mac_powerpc/i' => 'Mac OS 9',
            '/linux/i' => 'Linux',
            '/ubuntu/i' => 'Ubuntu',
            '/iphone/i' => 'iPhone',
            '/ipod/i' => 'iPod',
            '/ipad/i' => 'iPad',
            '/android/i' => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i' => 'Mobile'
        );

        foreach ($osArray as $regex => $value) {
            if (preg_match($regex, $userAgent)) {
                $osPlatform = $value;
            }
        }

        $browser = "Unknown Browser";
        $browserArray = array(
            '/msie/i' => 'Internet Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i' => 'Safari',
            '/chrome/i' => 'Chrome',
            '/edge/i' => 'Edge',
            '/opera/i' => 'Opera',
            '/netscape/i' => 'Netscape',
            '/maxthon/i' => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i' => 'Handheld Browser'
        );

        foreach ($browserArray as $regex => $value) {

            if (preg_match($regex, $userAgent)) {
                $browser = $value;
            }
        }

        $data['os_platform'] = $osPlatform;
        $data['browser'] = $browser;
        return $data;

    }

}

if (!function_exists('menuActive')) {
    function menuActive($routeName)
    {
        $class = 'active';
        if (is_array($routeName)) {
            foreach ($routeName as $key => $value) {
                if (request()->routeIs($value)) {
                    return $class;
                }
            }
        } elseif (request()->routeIs($routeName)) {
            return $class;
        }
    }
}

if (!function_exists('menuOpen')) {
    function menuOpen($routeName)
    {
        $class = 'active open';
        if (is_array($routeName)) {
            foreach ($routeName as $key => $value) {
                if (request()->routeIs($value)) {
                    return $class;
                }
            }
        } elseif (request()->routeIs($routeName)) {
            return $class;
        }
    }
}

if (!function_exists('Salam')) {
    function Salam () {
        $sekarang     = Carbon::now()->translatedFormat('A');
        $says         = Str::title('Selamat '.$sekarang);
        return  $says;
    }
}

if (!function_exists('data_karyawan')) {
    function data_karyawan()  {

    }
}

if(!function_exists('HashVariable')) {
    function HashVariable($data) {
        $hash = Crypt::encryptString($data);
        return $hash;
    }


}
if(!function_exists('EncryprVariable')) {
    function EncryprVariable($data) {
        $hash = Crypt::decryptString($data);
        return $hash;
    }
}

if (!function_exists('menuActiveDetailProfile')) {
    function menuActiveDetailProfile($routeName)  {
        $class = 'active';
        if (is_array($routeName)) {
            foreach ($routeName as $key => $value) {
                if (request()->routeIs($value)) {
                    return $class;
                }
            }
        } elseif (request()->routeIs($routeName)) {
            return $class;
        }
    }
}

if(!function_exists('Aktivitas')) {
    function Aktivitas($data) {
        Aktifitas::create([
            'id_user' => Auth::user()->id,
            'aktivitas' => $data,
            'id_client' => Auth::user()->id_client,
        ]);
    }
}

if(!function_exists('foto_profile')) {
    function foto_profile($id_karyawan) {
        $dataUser       = User::where("id_karyawan",$id_karyawan)->first();
        $roles          = $dataUser->roles;
        if($roles == 'superadmin') {
            $foto_profile = asset('assets/img/avatars/1.png');
        }else {
            $dataKaryawan   = Karyawan::where('id_karyawan',$id_karyawan)->first();
            $jenisKelamin   = $dataKaryawan->jenis_kelamin == 'L' ? 1 : 6 ;

            $cekPath        = Filemanager::where("id_karyawan",$id_karyawan)->where('slug','foto_profile')->first();
            if($cekPath) {
                $foto_profile = asset($cekPath->path);
            }else {
                $foto_profile = asset('assets/img/avatars/'.$jenisKelamin.'.png');

            }

        }
        return $foto_profile;
    }
}

if(!function_exists('getPathFile')){
    function getPathFile($idKaryawan,$slug) {
        $data       = Filemanager::where('id_karyawan',$idKaryawan)->where("slug",$slug)->first();
        $path       = $data->path;
        return $path;
    }
}

if(!function_exists('divisi')){
    function divisi($idKaryawan) {
        $data       = Karyawan::where('id_karyawan',$idKaryawan)->first();
        return $data->divisi;
    }
}

if(!function_exists('isAdmin')) {
    function isAdmin($id_karyawan) {

        if($id_karyawan == null || $id_karyawan == "" ) {
            return response()->json(['pesan' => 'ID karyawan dibutuhkan.'],422);
        }
        $user = User::where('id_karyawan',$id_karyawan)->first()->roles;

        if($user != 'admin') {
            return response()->json(['pesan' => 'Anda tidak memiliki akses.'],422);
        }

        return true;
    }
}

if(!function_exists('isSupervisorProject')){
    function isSupervisorProject($id_karyawan) {
        if($id_karyawan == null || $id_karyawan == "" ) {
            return response()->json(['pesan' => 'ID karyawan dibutuhkan.'],422);
        }
        $user = User::where('id_karyawan',$id_karyawan)->first()->roles;

        if($user != 'spv-internal') {
            return response()->json(['pesan' => 'Anda tidak memiliki akses.']);
        }

        return true;
    }
}

if(!function_exists('sendPushNotification')) {
    function sendPushNotification($userId,$title,$body) {
        $user = User::find($userId);

        if (!$user) {
            return ['success' => false, 'pesan' => 'Pengguna tidak ditemukan'];
        }

        if (!$user->device_token) {
            return ['success' => false, 'message' => 'Pengguna tidak memiliki token perangkat'];
        }

        $url        = 'https://fcm.googleapis.com/fcm/send';
        $serverKey  = 'AAAAtBY2JaE:APA91bHIat3y0uZHaqDTjucu0NH9FK05Gf0cjItIjU-n9dwExFN_Lf_TsmTUvMbSNUwzKbmTDTrrH7GJmB2FYbh0Piyf-YVGsoiaohWTV43a4lm6p85SD0ttgtBq-HHGls-UGP_pLwrZ';
        $data = [
            "to" => $user->device_token,
            "notification" => [
                "title" => $title,
                "body" => $body,
                ]
            ];
        $encodedData = json_encode($data);

        $headers = [
                'Authorization: key=' . $serverKey,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                return ['error' => curl_error($ch)];

                die('Curl failed: ' . curl_error($ch));
            }
            // Close connection
            curl_close($ch);
            return ['success' => $result];
    }
}


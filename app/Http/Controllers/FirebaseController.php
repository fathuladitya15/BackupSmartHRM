<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FirebaseController extends Controller
{

    function saveToken(Request $request) {
        auth()->user()->update(['device_token' => $request->not_token]);
        return response()->json(['Token Saved']);
        print_r($request->all());
    }
    public function sendNotification(Request $request)
    {
        $tokenList = User::whereNotNull('device_token')->pluck('device_token')->all();
        // $tokenList = [ []]
        // dd($tokenList);
        $serverKeys = 'AAAAtBY2JaE:APA91bHIat3y0uZHaqDTjucu0NH9FK05Gf0cjItIjU-n9dwExFN_Lf_TsmTUvMbSNUwzKbmTDTrrH7GJmB2FYbh0Piyf-YVGsoiaohWTV43a4lm6p85SD0ttgtBq-HHGls-UGP_pLwrZ';
        $data = [
            'registration_ids' => $tokenList,
            'notification'     => [
                'title'     => 'Test Notif',
                'body'      => 'Isinya',
            ]
        ];
        $encodedData = json_encode($data);
        $headers = [
            "Authorization:key=".$serverKeys,
            "Content-Type: application/json"
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,'https://fcm.googleapis.com/fcm/send');
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
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        echo($result);
        return redirect()->route('home');
        // return view('layouts.Dashboard.vAdminKorlap');
    }
}

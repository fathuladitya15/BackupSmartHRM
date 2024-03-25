<?php

namespace App\Http\Controllers\Api;

use Str;
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

        $login = Auth::Attempt($request->all());

        if ($login) {
            $user = Auth::user();
            $user->token = $this->generateToken($user->id);
            $user->save();
            $user->makeVisible('token');

            return response()->json([
                'response_code' => 200,
                'message' => 'Login Berhasil',
                'conntent' => $user
            ]);
        }else{
            return response()->json([
                'response_code' => 404,
                'message' => 'Username atau Password Tidak Ditemukan!'
            ]);
        }
    }
    private function generateToken($userId)
    {
        return hash('sha256', Str::random(40) . $userId);
    }
}

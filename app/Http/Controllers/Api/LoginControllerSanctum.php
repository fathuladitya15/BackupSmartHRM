<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|string|email',
            'password'  => 'required|string',
            'login_as'  => 'required|string|in:project,internal',
        ]);

        $credentials = $request->only('username', 'password');

        // return response()->json(['data' => $credentials]);

        // if (!Auth::attempt($credentials)) {
        //     throw ValidationException::withMessages([
        //         'username' => ['The provided credentials are incorrect.'],
        //     ]);
        // }

        // $user = Auth::user();
        // $loginAs = $request->input('login_as');

        // if ($user->roles == 'karyawan' && $loginAs != 'project') {
        //     return response()->json([
        //         'message' => 'Karyawan hanya dapat login melalui form karyawan.'
        //     ], 403);
        // }

        // // Logika tambahan untuk role lainnya
        // if ($loginAs == 'internal' && $user->role == 'spv-internal') {
        //     $user->role = 'kr-project';
        //     $user->save();
        // } elseif ($loginAs == 'supervisor' && $user->role == 'kr-project') {
        //     $user->role = 'spv-internal';
        //     $user->save();
        // } elseif ($loginAs == 'hrd' && $user->role != 'hrd') {
        //     $user->role = 'hrd';
        //     $user->save();
        // } elseif ($loginAs == 'manager' && $user->role != 'manager') {
        //     $user->role = 'manager';
        //     $user->save();
        // }

        // $token = $user->createToken('API Token')->plainTextToken;

        // return response()->json([
        //     'access_token' => $token,
        //     'token_type' => 'Bearer',
        //     'user' => $user,
        // ]);
    }
}

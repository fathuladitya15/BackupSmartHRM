<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class PeringatanController extends Controller
{
    function __contruct() {
        $this->middleware('auth');
    }

    function index() {
        $karyawan = User::where('id_client',Auth::user()->id_client)->where('roles','karyawan')->get();
        // dd($karyawan);
        return view("layouts.peringatan.vSPadmin",compact("karyawan"));
    }
}

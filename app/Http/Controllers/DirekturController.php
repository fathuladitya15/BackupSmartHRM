<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class DirekturController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }


    function index_lembur_direktur_hrd() {
        // dd(Auth::user());
        // return redirect()->route('lembur-direktur-data');
        return view('layouts.direktur.vLembur');
    }

    function data_lembur_dir_hrd(Request $request) {
        dd($request->all());
    }
}

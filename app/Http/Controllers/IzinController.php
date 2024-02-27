<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    function __contstruct() {
        $this->middleware('auth');
    }


    function index() {
        $id_karyawan    = Auth::user()->id_karyawan;
        $role           = Auth::user()->roles;
        $detail         = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first();
        $divisi         = Divisi::find($detail->divisi);
        $jabatan        = Jabatan::find($detail->jabatan);

        if($detail->lokasi_kerja != 3) {
            if($role == 'karyawan') {
                return view('layouts.izin.vIzinDefault',compact('detail','jabatan','divisi'));
            }else {
                dd($role);
            }
        }else {

            dd($role);
        }



    }
}

<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Models\Izin;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Filemanager;
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


    function saving(Request $request) {
        $id_izin    = $request->id_izin;
        $ttdCreate  = Filemanager::where("id_karyawan",Auth::user()->id_karyawan)->where('slug','signature')->first();

        if($id_izin == null) {
            if(Auth::user()->roles == 'karyawan' || Auth::user()->id_client != 3) {

                $createData = [
                    'karyawan_id'           => $request->id_karyawan,
                    'detail'                => $request->detail,
                    'alasan'                => $request->alasan,
                    'tanggal_pembuatan'     => $request->tanggal,
                    'jam_keluar'            => $request->waktu,
                    'ttd_karyawan'          => $ttdCreate->path,
                    'kembali'               => $request->kembali,
                    'status'                => 0,
                ];

                $pesan = ['status' => TRUE, 'title' => 'Sukses' ,'pesan' => 'Berhasil membuat izin keluar'];


            }

        }

        $create = Izin::create($createData);

        return response()->json($pesan);
    }
 }

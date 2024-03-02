<?php

namespace App\Http\Controllers;


use Auth;
use App\Models\KategoriCuti;
use Illuminate\Http\Request;

class CutiController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }

    function index() {
        $roles = Auth::user()->roles;

        if($roles == 'hrd') {
            return view('layouts.hrd.vCuti');
        }
    }

    function kategori_cuti() {
        return view('layouts.hrd.vKategori_cuti');
    }

    function kategori_cuti_save(Request $request){
        if($request->id_cuti == null) {
            KategoriCuti::create([
                'nama_kategori' => $request->nama_kategori,
            ]);
            $pesan = ['status' => TRUE,'title' => 'sukses' ,'pesan' => 'Berhasil menambahkan kategori cuti'];
        }else {
            $find = KategoriCuti::find($request->id_cuti);
            $find->nama_kategori = $request->nama_kategori;
            $find->update();
            $pesan = ['status' => TRUE,'title' => 'sukses' , 'pesan' => 'Kategori Cuti berhasil diperbarui'];

        }

        return response()->json($pesan);
    }

    function kategori_cuti_get(Request $request) {

        $data = KategoriCuti::find($request->id);

        if($data) {
            $status = ['status' => TRUE,'data' => $data];
        }else{
            $status = ['status' => FALSE];
        }

        return response()->json($status);
    }

    function kategori_cuti_delete(Request $request) {
        $get = KategoriCuti::findOrFail($request->id);
        $get->delete();
        if($get) {
            $status = ['status' => TRUE ,'title' => 'sukses','pesan' => "Kategori berhasil di hapus"];
        }else{
            $status = ['status' => FALSE,'title' => 'Error' ,'pesan' => 'Hubungi Tim IT terkait'];
        }
        return response()->json($status);

    }
}

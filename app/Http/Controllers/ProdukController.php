<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ListProdukImport;

class ProdukController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }

    function list_produk() {
        return view('layouts.admin_korlap.vListProduk');
    }

    function upload_list_produk(Request $request) {

        $file               = $request->file('files');
        $tipe_produk        = $request->tipe_produk;

        $upload = Excel::import(new ListProdukImport($tipe_produk),$file);
        if($upload) {
            $pesan = ['status' => TRUE,'title' => 'sukses' ,'pesan' => 'Sukses'];
        }else {
            $pesan = ['status' => FALSe,'title' => 'Error' ,'pesan' => 'Error'];
        }
        return response()->json($pesan);
    }
}

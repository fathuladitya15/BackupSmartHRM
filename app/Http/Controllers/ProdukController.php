<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ListProdukImport;
use Illuminate\Support\Facades\Validator;
use App\Models\ListProduk;

class ProdukController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }

    function list_produk() {
        $id_client = Auth::user()->clients()->first()->id;
        if($id_client == 2) {
            return view('layouts.admin_korlap.vListProduk');
        }
        else {
            abort(404);
        }
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

    function get_list_produk(Request $request) {
        $data = ListProduk::find($request->id);

        return response()->json($data);
    }

    function add_list_produk(Request $request) {
        $validator = Validator::make($request->all(), [
            'harga_produk' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'] // Hanya angka dan titik
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        ListProduk::create([
            'nama_produk' => $request->nama_produk,
            'no_produk'   => $request->nomor_produk,
            'satuan_produk' => $request->satuan_produk,
            'tipe_produk'   => $request->tipe_produk,
            'harga_produk' => $request->harga_produk,
            'id_client'     => Auth::user()->id_client,
        ]);

        return response()->json(['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Produk baru berhasil ditambahkan']);

    }

    function update_list_produk(Request $request) {

        $validator = Validator::make($request->all(), [
            'harga_produk' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'] // Hanya angka dan titik
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $u = ListProduk::find($request->id);

        $u->nama_produk = $request->nama_produk;
        $u->no_produk = $request->nomor_produk;
        $u->harga_produk = $request->harga_produk;
        $u->update();

        return response()->json(['status' => TRUE ,'title' => 'Sukses' ,'pesan' => 'Data berhasil diperbaharui']);
    }

    function delete_list_produk(Request $request) {
        $id = $request->id;

        $data = ListProduk::findOrFail($id);
        $data->delete();

        return response()->json(['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Produk Berhasil dihapus']);
    }
}

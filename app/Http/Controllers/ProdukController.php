<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Carbon\Carbon;
use App\Models\ListProduk;
use Illuminate\Http\Request;
use App\Imports\ListProdukImport;
use App\Models\ListLaporanProduksi;
use App\Models\DetailLaporanProduksi;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

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


    function laporan_produksi() {
        $client = Auth::user()->id_client;

        if($client == 2) {
            return view("layouts.admin_korlap.vLaporanProdukMegasari");
        }else {
            abort(404);
        }
    }

    function add_laporan_produksi(Request $request) {

        ListLaporanProduksi::create([
            'keterangan' => $request->keterangan,
            'from_date'   => $request->from_date,
            'to_date' => $request->to_date,
            'status' => 0,
            'id_client' => Auth::user()->id_client
        ]);

        return response()->json(['status' => TRUE ,'pesan' => 'Berhasil membuat laporan baru','title' => 'Sukses']);

    }

    function get_laporan_produksi(Request $request) {
        $data = ListLaporanProduksi::find($request->id);
        return response()->json($data);
    }

    function update_laporan_produksi(Request $request) {
        $u = ListLaporanProduksi::find($request->id) ;
        $u->from_date = $request->from_date;
        $u->to_date = $request->to_date;
        $u->keterangan = $request->keterangan;
        $u->update();

        return response()->json(['status' => TRUE ,'pesan' => 'Laporan telah diperbarui','title' => 'Sukses']);



    }

    function delete_laporan_produksi(Request $request) {
        $id = $request->id;

        $data = ListLaporanProduksi::findOrFail($id);
        $data->delete();

        return response()->json(['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Laporan PRoduksi Berhasil dihapus']);

    }

    function data_laporan_produksi(Request $request) {
        $cek = ListLaporanProduksi::find($request->id);
        $status = $cek->status;
        if($status != 0) {
            $link = null;
            $pesan = ['status' => TRUE,'title' => 'Mohon Maaf','pesan' => 'Laporan sedang ditinjau' ,'link' => $link];
        }else {
            $link =  route('laporan-produksi-detail',['id' => $request->id]);
            $dataProduk = ListProduk::where('id_client',Auth::user()->id_client)->get();

            $cekDetailLaporan = DetailLaporanProduksi::where('id_table_lap_period',$request->id)->count();

            if($cekDetailLaporan == 0) {
                foreach ($dataProduk as $key ) {
                    DetailLaporanProduksi::create([
                        'no_produk'     => $key->no_produk,
                        'nama_produk'   => $key->nama_produk,
                        'id_table_lap_period' => $request->id
                    ]);
                }
            }
            $pesan = ['status' => TRUE,'title' => 'Redirecting','pesan' => 'Redirect','link' => $link];
        }
        return response()->json($pesan);
    }

    function detail_laporan_produksi($id) {
        $data = ListLaporanProduksi::find($id);
        // Tanggal awal dan akhir yang ditentukan
        $startDate = Carbon::parse($data->from_date);
        $endDate = Carbon::parse($data->to_date);
        // Hitung total hari antara dua tanggal
        $totalDays = $endDate->diffInDays($startDate) + 1; // Ditambah 1 karena kita ingin menyertakan tanggal akhir juga

        if(Auth::user()->id_client == 2) {
            return view("layouts.admin_korlap.vDetailLaporanProduksiMegasari",compact('data','totalDays','startDate'));
        }else {
            abort(404);
        }
        // dd($id);
    }

    function get_detail_laporan_produksi(Request $request){
        $data   = DetailLaporanProduksi::find($request->id);
        $d      = $request->table;
        $values =   $data->$d;
        return response()->json(['data' => $data,'name' => $values]);
    }

    function update_detail_laporan_produksi(Request $request) {

        $validator = Validator::make($request->all(), [
            'total_produk' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'] // Hanya angka dan titik
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }
        // UPDATE VALUE TOTAL PRODUK TERLEBIH DAHULU
        $data   = DetailLaporanProduksi::find($request->id);
        $table          = $request->table_name;
        $data->$table   = $request->total_produk;
        $data->update();

        $a = [];
        for ($i=1; $i <= 31 ; $i++) {
            $name = 'tanggal_'.$i;
            $a[] = [
                'jumlah' => $data->$name
            ];
        };
        $total = array_sum(array_column($a, 'jumlah'));

        $hargaTotal = $total * $data->harga_produk_satuan;
        // UPDATE TOTAL PRODUK
        $updateLocalProduk                      = DetailLaporanProduksi::find($request->id);
        $updateLocalProduk->total_produk        = $total;
        $updateLocalProduk->total_harga_produk  = $hargaTotal;
        $updateLocalProduk->update();

        return response()->json(['status' => TRUE ,'title' => 'Sukses' ,'pesan' => 'Data berhasil diperbaharui','data' => $total]);

    }



    function laporan_produksi_get_totals(Request $request){
        $totals = [];

        for ($i=1; $i <= 31 ; $i++) {
            $data = DB::table('table_lap_produksi')->where('id_table_lap_period',$request->id)->selectRaw('SUM(CAST(tanggal_'.$i.' AS INT)) AS total')->first();

            $totals[] = [
                'total_tanggal_'.$i => $data->total == null ? 0 : $data->total,
            ];
        }

        $totalProduk = DB::table('table_lap_produksi')->where('id_table_lap_period',$request->id)->selectRaw('SUM(CAST(total_produk AS INT)) AS total')->first();
        $totalHargaProduk = DB::table('table_lap_produksi')->where('id_table_lap_period',$request->id)->selectRaw('SUM(CAST(total_harga_produk AS INT)) AS total')->first();

        return response()->json(['totals' => $totals,'totalProduk' => $totalProduk->total,'totalHargaProduk' => $totalHargaProduk->total]);
    }
}

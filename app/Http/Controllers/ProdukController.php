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

        $cek_list = ListProduk::where('id_client',Auth::user()->id_client)->where('tipe_produk',$tipe_produk)->count();
        if($cek_list > 0) {
            ListProduk::where('id_client',Auth::user()->id_client)->delete();
        }

        $upload = Excel::import(new ListProdukImport($tipe_produk),$file);

        if($upload) {
            $pesan = ['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Berhasil Upload Data Produk '];
        }
        else {
            $pesan = ['status' => FALSe,'title' => 'Terjadi Kesalahan !' ,'pesan' => 'Hubungi Tim IT !'];
        }
        return response()->json($pesan);
    }

    function get_list_produk(Request $request) {
        $data = ListProduk::find($request->id);

        return response()->json($data);
    }

    function add_list_produk(Request $request) {
        if($request->tipe_produk != 'n_primary') {
            $validator = Validator::make($request->all(), [
                'harga_produk' => ['required', 'regex:/^\d+(\.\d{1,2})?$/'] // Hanya angka dan titik
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 422);
            }
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

        $cekLaporan = ListLaporanProduksi::where('from_date',$request->from_date)->where('to_date',$request->to_date)->first();

        if($cekLaporan) {
            $pesan = ['status' => FALSE,'title' => 'Data Duplikat','pesan' => 'Laporan Produk dengan tanggal tersebut sudah tersedia, hapus terlebih dahulu jika ingin menggantinya'];
        }else {
            ListLaporanProduksi::create([
                'keterangan' => $request->keterangan,
                'from_date'   => $request->from_date,
                'to_date' => $request->to_date,
                'status' => 0,
                'id_client' => Auth::user()->id_client
            ]);
            $pesan = ['status' => TRUE ,'pesan' => 'Berhasil membuat laporan baru','title' => 'Sukses'];
        }

        return response()->json($pesan);

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
        DetailLaporanProduksi::where('id_table_lap_period',$id)->delete();

        return response()->json(['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Laporan PRoduksi Berhasil dihapus']);

    }

    function data_laporan_produksi(Request $request) {
        $cek = ListLaporanProduksi::find($request->id);
        $status = $cek->status;
        if($status != 0) {
            $link = null;
            $pesan = ['status' => TRUE,'title' => 'Mohon Maaf','pesan' => 'Laporan sedang ditinjau' ,'link' => $link];
        }else {
            $cekProduk = ListProduk::where('id_client',Auth::user()->id_client)->count();

            if($cekProduk == 0) {
                $link = null;
                $pesan = ['status' => TRUE,'title' => 'Produk tidak tersedia','pesan' => 'Cek terlebih dahulu produk anda' ,'link' => $link,'data' => $cekProduk];

            }else {
                $link =  route('laporan-produksi-detail',['id' => $request->id]);
                $dataProduk = ListProduk::where('id_client',Auth::user()->id_client)->get();

                $cekDetailLaporan = DetailLaporanProduksi::where('id_table_lap_period',$request->id)->count();

                if($cekDetailLaporan == 0) {
                    foreach ($dataProduk as $key ) {
                        DetailLaporanProduksi::create([
                            'no_produk'     => $key->no_produk,
                            'nama_produk'   => $key->nama_produk,
                            'harga_produk_satuan'   => $key->harga_produk,
                            'id_table_lap_period' => $request->id,
                            'tipe_produk' => $key->tipe_produk
                        ]);
                    }
                }

                $pesan = ['status' => TRUE,'title' => 'Redirecting','pesan' => 'Redirect','link' => $link];
            }
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

        $dataRekap = [];
        $result   = DetailLaporanProduksi::where('id_table_lap_period',$id)->orderBy('nama_produk','ASC')->get();

        for ($i=1; $i <=$totalDays ; $i++) {
            $currentDate    = $startDate->copy()->addDays($i);
            $t              = DetailLaporanProduksi::where('id_table_lap_period',$id);
            $names          = $t->selectRaw('SUM(CAST(tanggal_'.$i.' AS INT)) AS total')->first();
            $dataRekap['tanggal_'.$i] =  $names->total;
        }
        $totalProduk =  DB::table('table_lap_produksi')
            ->where('id_table_lap_period',$id)
            ->selectRaw('SUM(CAST(total_produk AS INT)) AS total')->first();

        $totalHargaProduk   = DB::table('table_lap_produksi')
            ->where('id_table_lap_period',$id)
            ->selectRaw('SUM(CONVERT(float,total_harga_produk)) AS total')->first()->total;

        $totalHargaProdukRP = "Rp. " .number_format(round($totalHargaProduk,2),2,',','.');
        $totalHargaProdukInt = round($totalHargaProduk,2);

        if(Auth::user()->id_client == 2) {
            return view("layouts.admin_korlap.vDetailLaporanProduksiMegasari",compact('data','totalDays','startDate','dataRekap','totalProduk','totalHargaProdukRP' ,'totalHargaProdukInt'));
        }else {
            abort(404);
        }
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

        $totalProduk        = DB::table('table_lap_produksi')->where('id_table_lap_period',$data->id_table_lap_period)->selectRaw('SUM(CAST(total_produk AS INT)) AS total')->first();
        $totalHargaProduk   = DB::table('table_lap_produksi')->where('id_table_lap_period',$data->id_table_lap_period)->selectRaw('SUM(CONVERT(float,total_harga_produk)) AS total')->first();

        $listProdukLap                      = ListLaporanProduksi::find($data->id_table_lap_period);
        $listProdukLap->total_produk        = $totalProduk->total;
        $listProdukLap->total_tagihan       = $totalHargaProduk->total;
        $listProdukLap->update();

        $totalSemuaHarga                    = "Rp. " .number_format(round($listProdukLap->total_tagihan,2),2,',','.');
        $totalHargaProdukInt                = round($listProdukLap->total_tagihan,2);


        return response()->json(['status' => TRUE ,'title' => 'Sukses' ,'pesan' => 'Data berhasil diperbaharui','hargaTotal' => $totalSemuaHarga ,'totalHargaProdukInt' => $totalHargaProdukInt]);

    }

    function laporan_produksi_get_totals(Request $request){


        $totals = [];

        for ($i=1; $i <= 31 ; $i++) {
            $data = DB::table('table_lap_produksi')
                    ->where('id_table_lap_period',$request->id)
                    ->where('tipe_produk',$request->tipe_produk)
                    ->selectRaw('SUM(CAST(tanggal_'.$i.' AS INT)) AS total')
                    ->first();

            $totals[] = [
                'total_tanggal_'.$i => $data->total == null ? 0 : number_format($data->total,0,',','.'),
            ];
        }

        $totalProduk =  DB::table('table_lap_produksi')
                        ->where('id_table_lap_period',$request->id)
                        ->where('tipe_produk',$request->tipe_produk)
                        ->selectRaw('SUM(CAST(total_produk AS INT)) AS total')->first();
        $totalHargaProduk   = DB::table('table_lap_produksi')
                            ->where('id_table_lap_period',$request->id)
                            ->where('tipe_produk',$request->tipe_produk)
                            ->selectRaw('SUM(CONVERT(float,total_harga_produk)) AS total')->first();

        $result = [
            'totals'            => $totals,
            'totalProduk'       => $totalProduk->total,
            'totalHargaProduk'  => "Rp. " .number_format(round($totalHargaProduk->total,2),2,',','.'),



        ];
        return response()->json($result);
    }

    function laporan_produksi_get_calculate(Request $request) {
        $total          = $request->harga;
        $percentage     = $request->fee;
        if($percentage == 'NaN'){
            $toRp           =  "Rp. " .number_format(round(0,2),2,',','.');
            return response()->json(['rp' => $toRp]);

        }else{
            $result         = $total * ($percentage / 100);
            $totalF         = $total + $result;
            $toRp           =  "Rp. " .number_format(round($totalF,2),2,',','.');
            return response()->json(['rp' => $toRp]);
        }

    }
}

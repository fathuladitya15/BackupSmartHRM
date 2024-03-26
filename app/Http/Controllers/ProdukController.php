<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use DataTables;
use Carbon\Carbon;
use App\Models\ListProduk;
use App\Models\CekSelisih;
use App\Models\LaporanSPV;
use Illuminate\Http\Request;
use App\Imports\ListProdukImport;
use App\Models\ListLaporanProduksi;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DetailLaporanProduksi;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }

    // LIST PRODUK
    function list_produk() {
        $id_client = Auth::user()->clients()->first()->id;
        if(in_array($id_client, [2,8])) {
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
            $pesan = ['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Berhasil Upload Data Produk ','tipe' => $cek_list];
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

    // END LIST PRODUK

    // LIST LAPORAN PRODUK

    function laporan_produksi(Request $request) {


        if(in_array(Auth::user()->roles,['admin','korlap'])) {
            return view("layouts.admin_korlap.vLaporanProdukMegasari");
        }else if(Auth::user()->roles == 'spv-internal') {
            return view('layouts.spv.vLaporanProduksiMegasari');
        }else {
            abort(404,'Halaman Tidak ditemukan');
        }
    }

    function laporan_produksi_yp(Request $request,$kategori) {
        if(in_array(Auth::user()->roles,['admin','korlap'])) {
            return view("layouts.admin_korlap.vLaporanProdukYupi");
        }else if(Auth::user()->roles == 'spv-internal') {
            return view('layouts.spv.vLaporanProduksiMegasari');
        }else {
            abort(404,'Halaman Tidak ditemukan');
        }
    }

    function add_laporan_produksi(Request $request) {

        $cekLaporan = ListLaporanProduksi::where('from_date',$request->from_date)
        ->where('to_date',$request->to_date)
        ->where('id_client',Auth::user()->id_client)->first();

        $tanggal        = Carbon::parse($request->from_date)->format('d'); // MENGAMBIL TANGGAL SAJA DARI REQUEST YANG DIKIRIM
        $tanggal_las    = Carbon::parse($request->from_date)->endOfMonth(); // MENGAMBIL TOTAL HARI DALAM BULAN YANG DIMAKSUD

        $month_1 = Carbon::parse($request->from_date)->format('d-m-y'); // CEK TANGGAL APAKAH MULAI DARI TANGGAL 1 ATAU TIDAK
        $month_2 = Carbon::parse($request->to_date)->format('d-m-y');

        if($cekLaporan) { // CEK TANGGAL APAKAH YANG DIMAKSUD DUPLICATE ATAU TIDAK
            $pesan = ['status' => FALSE,'title' => 'Data Duplikat','pesan' => 'Laporan Produk dengan tanggal tersebut sudah tersedia, hapus terlebih dahulu jika ingin menggantinya'];
        }
        else if($tanggal != '01'){ // TANGGAL HARUS DIMULAI DARI TANGGAL 1
            $pesan = ['status' => FALSE,'title' => 'Tanggal tidak valid','pesan' => 'Laporan harus dimulai dari tanggal 1'];
        }
        else if($tanggal_las->format('Y-m-d') != $request->to_date) { // APAKAH TANGGAL SATU PADA BULAN YANG DI REQUEST SAMA ATAU TIDAK
            $pesan = ['status' => FALSE,'title' => 'Tanggal Tidak valid', 'pesan' => 'Tanggal laporan harus 1 bulan, mulai dari tanggal 1 sampai dengan selesai'];
        }
        else {
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

        if(in_array(Auth::user()->roles,['admin','korlap'])) {
            if($u->status != 0) {
                $pesan = ['status' => FALSE,'pesan' => 'Data ini belum bisa perbaharui karena masih dalam proses' ,'title' => 'Mohon Maaf ...'];
            }else {
                $u->from_date   = $request->from_date;
                $u->to_date     = $request->to_date;
                $u->keterangan  = $request->keterangan;
                $u->update();
                $pesan = ['status' => TRUE ,'pesan' => 'Laporan telah diperbarui','title' => 'Sukses'];
            }

        }else if(Auth::user()->roles == 'spv-internal') {
            $u->status = $request->status;
            $u->update();
            if($request->status == 2) {
                $pesan = ['status' => TRUE,'pesan' => 'Laporan telah disetujui','title' => 'Sukses'];
            }else if($request->status == 3) {
                $pesan = ['status' => TRUE,'pesan' => 'Laporan ditolak, dikembalikan ke admin','title' => 'Sukses'];
            }
        }else {
            $pesan = ['status' => FALSE ,'pesan' => 'Terjadi kesalahan','title' => 'Error'];

        }

        return response()->json($pesan);
    }

    function delete_laporan_produksi(Request $request) {
        $id = $request->id;

        $data = ListLaporanProduksi::findOrFail($id);
        if($data->status != 0){
            $pesan = ['status' => FALSE,'pesan' => 'Data ini belum bisa dihapus karena masih dalam proses' ,'title' => 'Mohon Maaf ...'];
        }else {
            $pesan = ['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Laporan PRoduksi Berhasil dihapus','data' => $data->status];

            $data->delete();
            DetailLaporanProduksi::where('id_table_lap_period',$id)->delete();
        }

        return response()->json($pesan);

    }

    function data_laporan_produksi(Request $request) {
        $cek = ListLaporanProduksi::find($request->id);
        $status = $cek->status;
        if(in_array(Auth::user()->roles,['admin','korlap'])){
            if(Auth::user()->id_client == 2){
                if(in_array($status,[1])) {
                    $link = null;
                    $pesan = ['status' => TRUE,'title' => 'Mohon Maaf ...','pesan' => 'Laporan ini sedang diriview supervisor' ,'link' => $link];
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
            }else if(Auth::user()->id_client == 8 ){
                if($status == 1) {
                    $link = null;
                    $pesan = ['status' => TRUE,'title' => 'Mohon Maaf ...','pesan' => 'Laporan ini sedang diriview supervisor' ,'link' => $link];
                }else {
                    $cekProduk = ListProduk::where('id_client',Auth::user()->id_client)->where('tipe_produk',$request->tipe_produk)->count();

                    if($cekProduk == 0) {
                        $link = null;
                        $pesan = ['status' => TRUE,'title' => 'Produk tidak tersedia','pesan' => 'Cek terlebih dahulu produk anda','s' => $request->tipe_produk ];

                    }else {
                        $link =  route('laporan-produksi-detail',['id' => $request->id]);
                        $dataProduk = ListProduk::where('id_client',Auth::user()->id_client)->where('tipe_produk',$request->tipe_produk )->get();

                        $cekDetailLaporan = DetailLaporanProduksi::where('id_table_lap_period',$request->id)->where('tipe_produk',$request->tipe_produk )->count();

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

                        $pesan = ['status' => TRUE,'title' => 'Redirecting','pesan' => 'Redirect','link' => $link,'dataProduk' => $dataProduk];
                    }
                }
            }
        }else if(Auth::user()->roles == 'spv-internal') {
            $link =  route('laporan-produksi-detail',['id' => $request->id]);
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

        $dataRekap = [];
        $result   = DetailLaporanProduksi::where('id_table_lap_period',$id)->orderBy('nama_produk','ASC')->get();

        if(Auth::user()->id_client == 2) {
            for ($i=1; $i <=$totalDays ; $i++) {
                $currentDate    = $startDate->copy()->addDays($i);
                $t              = DetailLaporanProduksi::where('id_table_lap_period',$id);
                $names          = $t->selectRaw('SUM(CAST(tanggal_'.$i.' AS INT)) AS total')->first();
                $dataRekap['tanggal_'.$i] =  $names->total;
            }
            $totalProduk =  DB::table('table_lap_produksi')
                ->where('id_table_lap_period',$id)
                ->selectRaw('SUM(CAST(total_produk AS INT)) AS total')->first();

        }else {
            for ($i=1; $i <=$totalDays ; $i++) {
                $currentDate    = $startDate->copy()->addDays($i);
                $t              = DetailLaporanProduksi::where('id_table_lap_period',$id);
                $names          = $t->selectRaw('SUM(CONVERT(float,tanggal_'.$i.')) AS total')->first();
                $dataRekap['tanggal_'.$i] =  $names->total;
            }
            $totalProduk_C =  DB::table('table_lap_produksi')
                ->where('id_table_lap_period',$id)
                ->selectRaw('SUM(CONVERT(float,total_produk)) AS total')->first();
            $totalProduk = number_format($totalProduk_C->total,2,'.','.');

        }


        $totalHargaProduk   = DB::table('table_lap_produksi')
            ->where('id_table_lap_period',$id)
            ->selectRaw('SUM(CONVERT(float,total_harga_produk)) AS total')->first()->total;

        $totalHargaProdukRP = "Rp. " .number_format(round($totalHargaProduk,2),2,',','.');
        $totalHargaProdukInt = round($totalHargaProduk,2);


        if(Auth::user()->id_client == 2) {
            return view("layouts.admin_korlap.vDetailLaporanProduksiMegasari",compact('data','totalDays','startDate','dataRekap','totalProduk','totalHargaProdukRP' ,'totalHargaProdukInt'));
        }else if(Auth::user()->id_client == 8) {
            return view('layouts.admin_korlap.vDetailLaporanProduksiYupi',compact('data','totalDays','startDate','dataRekap','totalProduk','totalHargaProdukRP' ,'totalHargaProdukInt'));
        }
        else {
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
        ],['total_produk.regex' => 'Hanya angka dan titik yang diperbolehkan' ]);

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

        if(Auth::user()->id_client == 2) {
            $totalProduk        = DB::table('table_lap_produksi')->where('id_table_lap_period',$data->id_table_lap_period)->selectRaw('SUM(CAST(total_produk AS INT)) AS total')->first();
        }
        else {
            $totalProduk        = DB::table('table_lap_produksi')->where('id_table_lap_period',$data->id_table_lap_period)->selectRaw('SUM(CONVERT(float,total_produk )) AS total')->first();

        }
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

        if(Auth::user()->id_client == 2){// PERHITUNGAN UNTUK PRODUK MEGASARI
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
        }
        else { //PERHITUNGAN YUPI
            for ($i=1; $i <= $request->hari ; $i++) {
                $data = DB::table('table_lap_produksi')
                        ->where('id_table_lap_period',$request->id)
                        ->where('tipe_produk',$request->tipe_produk)
                        ->selectRaw('SUM(CONVERT(float,tanggal_'.$i.')) AS total')
                        ->first();

                $totals[] = [
                    'total_tanggal_'.$i => $data->total == null ? 0.00 : number_format($data->total,2,',','.'),
                ];
            }

            $totalProduk =  DB::table('table_lap_produksi')
                            ->where('id_table_lap_period',$request->id)
                            ->where('tipe_produk',$request->tipe_produk)
                            ->selectRaw('SUM(CONVERT(float,total_produk)) AS total')->first();
        }


        $totalHargaProduk   = DB::table('table_lap_produksi')
                            ->where('id_table_lap_period',$request->id)
                            ->where('tipe_produk',$request->tipe_produk)
                            ->selectRaw('SUM(CONVERT(float,total_harga_produk)) AS total')->first();

        $result = [
            'totals'            => $totals,
            'totalProduk'       => number_format($totalProduk->total,2,'.','.'),
            'totalHargaProduk'  => "Rp. " .number_format(round($totalHargaProduk->total,2),2,',','.'),



        ];
        return response()->json($result);
    }

    function laporan_produksi_get_calculate(Request $request) {
        $total          = $request->harga;
        $percentage     = $request->fee;
        if($percentage == 'NaN'){
            $toRp           =  "Rp. " .number_format(round(0,1),2,',','.');
            return response()->json(['rp' => $toRp]);

        }else{
            $result         = $total * ($percentage / 100);
            $totalF         = $total + $result;
            $toRp           =  "Rp. " .number_format(round($totalF,1),2,',','.');
            return response()->json(['rp' => $toRp]);
        }

    }

    function laporan_produksi_kirim(Request $request) {
        $lins = route('laporan-produksi');
        if (Auth::user()->id_client == 2) {
            $result          = $request->mentahan_harga * ($request->fee / 100);
            $hasilPersentase = number_format($result,2,',','.');

            $dataUpdate = [
                'status' => 1,
                'persentase' => $request->fee,
                'hasil_persentase' => $hasilPersentase,
            ];
            $u = ListLaporanProduksi::where("id",$request->id_list_laporan)->update($dataUpdate);
            $pesan = ['status' => TRUE,'title' => 'Data Terkirim' ,'pesan' => 'Data berhasil dikirim ke supervisor. Halaman anda dialihkan ','link' => $lins,'data' => $u];
            Aktivitas(Auth::user()->name.' Menyetujui laporan produksi '.$u->keterangan.'('.$u->from_date.'/'.$u->to_date.')');
        }else if(Auth::user()->id_client == 8){
            $u = ListLaporanProduksi::find($request->id_list_laporan);
            if($request->status == 2 ) {
                $u->status         = $request->status;
                $u->disetujui_pada = Carbon::now();
                $u->disetujui_pada = Auth::user()->name.'(Supervisor)';
                $u->update();
                $pesan = ['status' => TRUE,'title' => 'Laporan disetujui' ,'pesan' => 'Laporan berhasil disetujui. Halaman anda dialihkan ','link' => $lins,'data' => $u];
                Aktivitas(Auth::user()->name.' Menyetujui laporan produksi '.$u->keterangan.'('.$u->from_date.'/'.$u->to_date.')');
            }else {
                $u->status         = $request->status;
                $u->update();
                $pesan = ['status' => TRUE,'title' => 'Laporan ditolak' ,'pesan' => 'Laporan dikembalikan ke admin/korlap. Halaman anda dialihkan ','link' => $lins,'data' => $u];
                Aktivitas(Auth::user()->name.' Menolak laporan produksi '.$u->keterangan.'('.$u->from_date.'/'.$u->to_date.')');

            }
        }


        return response()->json();
    }

    // LAPORAN YUPI
    function laporan_produksi_yupi($id, $tipe_produk) {
        $data       = ListLaporanProduksi::find($id);

        // Tanggal awal dan akhir yang ditentukan
        $startDate  = Carbon::parse($data->from_date);
        $endDate    = Carbon::parse($data->to_date);

        // Hitung total hari antara dua tanggal
        $totalDays = $endDate->diffInDays($startDate) + 1;
        $data = [];

        for($i = 1; $i <= $totalDays; $i++){
            $total = DetailLaporanProduksi::where('id_table_lap_period',$id)->selectRaw('SUM(CONVERT(float,tanggal_'.$i.')) AS total')->first();
            $cekValuInput = CekSelisih::where('id_table_lap_produksi',$id)->where('tanggal','tanggal_'.$i);
            $data[] =[
                'tanggal'       => 'Tanggal '.$i,
                'total_produk'=> number_format($total->total,2,'.',''),
                'value_name'    => 'tanggal_'.$i,
                'value_input'   => $cekValuInput->count() == 1 ? $cekValuInput->first()->TotalCountProduk_M : "",
                'no'            => $i,
            ];
        }

        $dt     = DataTables::of($data)
        ->addColumn('input', function($row) use ($id) {
            $html = '<form id="formSelisih_'.$row['no'].'">';
            if(Auth::user()->roles == 'spv-internal') {
                $html .= '<input class="form-control-sm" type="text" name="input_selisih" id="input_selisih" placeholder="0.00" value="'.$row['value_input'].'" readonly>';
            }else {
                $html .= '<input class="form-control-sm" type="text" name="input_selisih" id="input_selisih" placeholder="Masukan data anda" value="'.$row['value_input'].'" >';
                $html .= '<input type="hidden" name="tanggal" id="tanggal" value='.$row['value_name'].'>';
                $html .= '<input type="hidden" name="total_produk" id="total_produk" value='.$row['total_produk'].'>';
                $html .= '<input type="hidden" name="id_table_lap_period" id="id_table_lap_period" value='.$id.'>';
                $html .= ' <a href="javascript:void(0)" onclick="cek_selisih('.$row['no'].')" type="button" class="btn btn-sm btn-primary" >Cek</a>';
            }

            $html .= '</form>';

            return $html;
        })
        ->addColumn('status', function($row) use ($id) {
            $cekDB      = CekSelisih::where('tanggal',$row['value_name'])->where('id_table_lap_produksi',$id);
            if($cekDB->count() == 1) {
                $data = $cekDB->first();
                if($data->status == 0) {
                    $status = '<span class="badge badge bg-danger badge-sm">Selisih</span>';
                }else {
                    $status = '<span class="badge badge bg-success badge-sm">OK</span>';
                }
            }else {
                $status = "";
            }
            return $status;
        })
        ->addcolumn('selisih', function($row) use ($id) {
            $cekDB      = CekSelisih::where('tanggal',$row['value_name'])->where('id_table_lap_produksi',$id);
            if($cekDB->count() != null) {
                $data = $cekDB->first();
                $selisih = $data->selisih;
            }else {
                $selisih = 00.00;
            }
            return $selisih;
        })
        ->rawColumns(['input','selisih','status'])
        ->make(true);
        return $dt;
    }

    function laporan_produksi_yupi_cek_selisih(Request $request) {

        $validasi = Validator::make($request->all(), [
            'input_selisih' => ['required', 'regex:/^[0-9.]{6,}$/'] // Hanya angka dan koma
        ],['input_selisih.regex' => 'Hanya angka titik dan koma yang diperbolehkan' ]);

        if ($validasi->fails()) {
            return response()->json(['errors' => $validasi->errors()->all()], 422);
        }
        $cekDB      = CekSelisih::where('tanggal',$request->tanggal)->where('id_table_lap_produksi',$request->id_table_lap_period)->count();
        $idMSelisih = CekSelisih::where('tanggal',$request->tanggal)->where('id_table_lap_produksi',$request->id_table_lap_period)->first();
        $status     = $request->total_produk == $request->input_selisih ? TRUE : FALSE;
        $selisih    = (float)$request->total_produk - (float) $request->input_selisih;
        $data = [
            'id_table_lap_produksi'   => $request->id_table_lap_period,
            'tanggal'                 => $request->tanggal,
            'TotalCountProduk_DB'     => $request->total_produk,
            'TotalCountProduk_M'      => $request->input_selisih,
            'status'                  => $status,
            'selisih'                 => number_format((float)$selisih,2,'.','.'),
        ];
        if($cekDB == 0){
            CekSelisih::create($data);
        }else {
            CekSelisih::where('id',$idMSelisih->id)->update([
                'TotalCountProduk_DB'     => $request->total_produk,
                'TotalCountProduk_M'      => $request->input_selisih,
                'status'                  => $status,
                'selisih'                 => number_format((float)$selisih,2,'.','.'),
            ]);
        }
        Aktivitas(Auth::user()->name.' Memperbarui Selisih Tonase ');
        return response()->json(['status' => TRUE,'pesan' => 'Data Berhasil diupdate' ,'title' => 'Sukses' ,'asd' => $request->all()]);
        // return response()->json($idMSelisih->id);
    }

    function laporan_produksi_compare($id,$tipe_produk) {
        $totalProduk =  DB::table('table_lap_produksi')
            ->where('id_table_lap_period',$id)
            ->where('tipe_produk',$tipe_produk)
            ->selectRaw('SUM(CONVERT(float,total_produk)) AS total')->first();
        $totalProdukM =  DB::table('table_selisih_yupi')
            ->where('id_table_lap_produksi',$id)
            ->selectRaw('SUM(CONVERT(float,TotalCountProduk_M)) AS total')->first();
        $selisih =  ($totalProdukM->total/1000) - ($totalProduk->total/ 1000);
        $data = [
            [
                'name' => 'yupi',
                'total' => number_format($totalProduk->total/ 1000,5,'.',''),
            ],[
                'name' => 'pfi',
                'total' => number_format($totalProdukM->total/1000,5,'.',''),
            ], [
                'name' => 'selisih',
                'total'=>number_format($selisih,5,'.',''),
            ]
        ];
        $dt     = DataTables::of($data)
        ->addColumn('satuan', function() {
            return "TON";
        })
        ->rawcolumns(['satuan'])
        ->make(true);
        return $dt;
    }
}

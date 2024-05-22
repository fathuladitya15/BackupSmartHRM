<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PreOrder;
use App\Models\BarangPreOrder;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Clients;
use App\Models\Filemanager;
use Carbon\Carbon;
use DB;
use PDF;
use Str;

class PreorderController extends Controller
{
    function dataPreOrder(Request $request) {
        $id_karyawan    = $request->id_karyawan;
        $id_client      = $request->id_client;

        if($id_karyawan == null || $id_karyawan == ""){
            return response()->json(['pesan' => 'ID Karyawan dibutuhkan'],422);
        }

        if($id_client == null || $id_client == "") {
            return response()->json(['pesan' => 'ID Client dibutuhkan'],422);
        }

        $DataKaryawan   = Karyawan::where('id_karyawan',$id_karyawan)->first();
        $Divisi         = Karyawan::where('id_karyawan',$id_karyawan)->first()->divisi()->first()->nama_divisi;
        $Jabatan        = Karyawan::where('id_karyawan',$id_karyawan)->first()->jabatan()->first()->nama_jabatan;

        if(!in_array($Divisi, ['MPO','General Affair ( GA )'])) {
            return response()->json(['pesan' => 'Anda tidak mempunyai akses'],401);
        }

        $Data           = PreOrder::where('id_client',$request->id_client)->orderBy('created_at','DESC')->get();
        $result         = [];

        foreach ($Data as $key) {
            $result[]   =   [
                'id'            => $key['id'],
                'nomor_po'      => $key['no_po'],
                'bulan'         => $key['bulan'],
                'tanggal'       => Carbon::parse($key['tanggal'])->translatedFormat('d F Y'),
                'divisi'        => $key['divisi'],
                'dibuat_oleh'   => $key['dibuat_oleh'],
                'batas_waktu'   => Carbon::parse($key['batas_waktu'])->translatedFormat('d F Y'),
                'jumlah'        => $this->HitungJumlahPreOrder($key['id']),
                'status'        => $this->info_status($key['status']),
                'acc'           => $key['status'] == 2 ? 1 : 0,
                'detail_request'=> $this->data_barang($key['id']),
                'link'          => $key['status'] == 2 ? $this->getLinkDownload($key['id']) : "",
            ];
        }

        return response()->json(['Divisi' => $Divisi,'Jabatan' => $Jabatan,'data' => $result],200);
    }

    function getLinkDownload($id) {
        $url = route("download-preorder",['id' => $id ]);
        return $url;
    }

    function viewFile($id) {
        $data   = PreOrder::find($id);
        $barang = BarangPreOrder::where("id_pre_order",$id)->get();
        $jumlah = DB::table('table_barang_po')
                ->where('id_pre_order',$id)

                ->sum('jumlah');
                // ->selectRaw('SUM(CAST(jumlah as int)) AS Total')
                // ->where('id_pre_order',$id)
                // ->first();
        $tahun      = Carbon::parse($data->tanggal)->format('Y');
        $nama_pt    = Clients::where('id',$data->id_client)->first()->nama_client;
        $rupiah     = number_format($jumlah,0,'.',',');
        // dd($jumlah->Total);
        $judul      = 'PURCHASE REQUISITION '. Str::upper($nama_pt).' BULAN '.Str::upper($data->bulan).' '.$tahun;
        $filename   = 'PURCHASE REQUISITION '. Str::upper($nama_pt).' BULAN '.Str::upper($data->bulan).' '.$tahun.'pdf';
        $pdf        = PDF::loadview('layouts.pdf_view.pdfPreOrder',['data' => $data,'judul' => $judul,'data_barang'=>$barang,'jumlah' => $jumlah]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->download($filename);
    }

    function data_barang($id) {
        $hitungData     = DB::table('table_barang_po')->where('id_pre_order',$id)->count();

        if($hitungData > 1) {
            $data           = DB::table('table_barang_po')->where('id_pre_order',$id)->get();
            $result = [];

            foreach ($data as $key ) {
                $result[] = [
                    // 'id'            => $key['id'],
                    'nama_barang'   => $key['nama_barang'],
                    'total_barang'  => $key['unit'],
                    'satuan_barang' => $key['satuan'],
                    'biaya'         => $this->formatRupiah($key['biaya']),
                    'jumlah'        => $this->formatRupiah($key['jumlah']),

                ];
            }

        }else {
            $data           = DB::table('table_barang_po')->where('id_pre_order',$id)->first();
            $result = [
                // 'id'            => $data->id,
                'nama_barang'   => $data->nama_barang,
                'total_barang'  => $data->unit,
                'satuan_barang' => $data->satuan,
                'biaya_per_barang'  => $this->formatRupiah($data->biaya),
                'jumlah'        => $this->formatRupiah($data->jumlah),
            ];
        }

        return $result;
    }

    function HitungJumlahPreOrder($id) {
        $total = DB::table('table_barang_po')->where('id_pre_order',$id)->sum('jumlah');

        return $this->formatRupiah($total);
    }

    function formatRupiah($data) {
        return  'Rp. '.number_format($data,0,'.',',') ;
    }

    function info_status($status) {
        if($status == 0) {
            $p  = 'Menunggu Disetujui';
        }
        else if($status == 1) {
            $p  = ' Menunggu tanda tangan direktur ';
        }
        else if($status == 2) {
            $p  = ' Telah disetujui & ditandatangan  ';
        }
        else if($status == 5) {
            $p  = ' Ditolak ( Direview Ulang )';
        }
        else {
            $p  =  'Status tidak diketahui';
        }

        return $p;
    }

    function acc_spv_internal(Request $request) {
        $id_karyawan    = $request->id_karyawan;

        if($id_karyawan == null || $id_karyawan == ""){
            return response()->json(['pesan' => 'ID Karyawan dibutuhkan'],422);
        }

        $DataUser       = User::where("id_karyawan",$id_karyawan)->first();


        $idPreOrder         =   $request->id_pre_order;
        $id_client_PreORder = PreOrder::find($idPreOrder)->id_client;

        if($DataUser->roles != 'spv-internal') {
            return response()->json(['pesan' => 'Anda tidak mempunyai akses'],401);
        }

        if($DataUser->id_client != $id_client_PreORder) {
            return response()->json(['pesan' => 'Anda tidak mempunyai akses.'],401);
        }

        if($idPreOrder == "" || $idPreOrder == null) {
            return response()->json(['pesan' => 'Id Permintaan Pembelian dibutuhkan'],422);
        }

        $getData                    = PreOrder::find($idPreOrder);
        $getData->status            = 1;
        $getData->disetujui_oleh    = $DataUser->name;
        $getData->update();

        return response()->json(['pesan' => 'Permintaan Pembelian berhasil di setujui.'],200);

    }

    function reject_spv_internal(Request $request) {

    }

    function ttd_direktur(Request $request) {
        $id_karyawan    = $request->id_karyawan;

        if($id_karyawan == null || $id_karyawan == ""){
            return response()->json(['pesan' => 'ID Karyawan dibutuhkan'],422);
        }

        $DataKaryawan   = Karyawan::where("id_karyawan",$id_karyawan)->first();
        $Jabatan        = $DataKaryawan->jabatan()->first()->nama_jabatan;
        $Divisi         = $DataKaryawan->divisi()->first()->nama_divisi;

        if($Jabatan != 'Direktur' || $Divisi != 'MPO') {
            return response()->json(['pesan' => 'Anda tidak mempunyai akses.'],401);
        }

        $idPreOrder     =   $request->id_pre_order;

        if($idPreOrder == "" || $idPreOrder == null) {
            return response()->json(['pesan' => 'Id Permintaan Pembelian dibutuhkan'],422);
        }

        $ttdPath                =   Filemanager::where('id_karyawan',$id_karyawan)->where('slug','signature')->first()->path;

        $getData                =   PreOrder::find($idPreOrder);
        $getData->status        = 2;
        $getData->ttd_direktur  = $ttdPath;
        $getData->nama_direktur = $DataKaryawan->nama_karyawan;
        $getData->update();

        return response()->json(['pesan' => 'Permintaan Pembelian berhasil disetujui dan ditandatangani.'],200);
    }

    function getPreOrderAdmin(Request $request) {
        $id_karyawan = $request->id_karyawan;

        if($id_karyawan  == null || $id_karyawan  == "") {
            return response()->json(['pesan' => "ID Karyawan dibutuhkan."],422);
        }

        $cekID = User::where('id_karyawan',$id_karyawan);

        if($cekID->count() == 0){
            return response()->json(['pesan' => "ID Karyawan tidak terdaftar."],404);
        }

        $dataUser = $cekID->first();

        if(!in_array($dataUser->roles,['admin','korlap'])) {
            return response()->json(['pesan' => 'Anda tidak memiliki akses.'],401);
        }

        $id_client = $dataUser->id_client;

        $data = PreOrder::where("id_client", $id_client)->where('id_user',$dataUser->id)->get();

        $result = [];
        foreach($data as $key) {
            $result[] = [
                'id'            => $key->id,
                'nomor_po'      => $key->no_po,
                'bulan'         => $key->bulan,
                'tanggal'       => Carbon::parse($key->tanggal)->translatedFormat('d F Y'),
                'divisi'        => $key->divisi,
                'dibuat_oleh'   => $key->dibuat_oleh,
                'batas_waktu'   => Carbon::parse($key->batas_waktu)->translatedFormat('d F Y'),
                'jumlah'        => $this->HitungJumlahPreOrder($key->id),
                'status'        => $this->info_status($key->status),
                'acc'           => $key->status == 2 ? 1 : 0,
                'detail_request'=> $this->data_barang($key->id),
                'link'          => $key->status == 2 ? $this->getLinkDownload($key->id) : "",
            ];
        }

        return response()->json(['data' => $result]);
    }

    function createPreOrderAdmin(Request $request) {
        $id_karyawan = $request->id_karyawan;

        if($id_karyawan  == null || $id_karyawan  == "") {
            return response()->json(['pesan' => "ID Karyawan dibutuhkan."],422);
        }

        $cekID = User::where('id_karyawan',$id_karyawan);

        if($cekID->count() == 0){
            return response()->json(['pesan' => "ID Karyawan tidak terdaftar."],404);
        }

        $dataUser = $cekID->first();

        if(!in_array($dataUser->roles,['admin','korlap'])) {
            return response()->json(['pesan' => 'Anda tidak memiliki akses.'],401);
        }

        $id_client = $dataUser->id_client;

        return response()->json(['data' => $id_client]);
    }

}

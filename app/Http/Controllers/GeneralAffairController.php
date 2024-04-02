<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use DataTables;
use App\Models\PreOrder;
use Illuminate\Http\Request;

class GeneralAffairController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }


    function index($client) {
        $id_client = EncryprVariable($client);
        // dd($id_client);
        return view('layouts.GeneralAffair.vPreOrderGA',compact('id_client'));
    }

    function data(Request $request) {

        $data  = PreOrder::where('id_client',$request->id_client)->get();
        $dt     = DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('aksi', function($row) {
             $file       = '<a href="'.route('pre-order-dokumen',['id' => HashVariable($row->id)]).'" class="btn btn-primary btn-sm" id="file_'.$row->id.'" target="BLANK_"  ><i class="bx bx-download"></i>Lihat File</a>';

            if($row->status == 2) {
                return $file;
            }
            return "";

        })
        ->addColumn('status', function($row) {
            if($row->status == 0) {
                $p = '<span class="badge badge bg-warning"> Menunggu Disetujui  </span>';
            }
            else if($row->status == 1) {
                $p = '<span class="badge badge bg-warning">  Menunggu tanda tangan direktur  </span>';
            }
            else if($row->status == 2){
                $p = '<span class="badge badge bg-success"> Telah disetujui & ditandatangan  </span>';
            }
            else if($row->status == 5) {
                $p = '<span class="badge badge bg-danger"> Ditolak ( Direview Ulang )  </span>';
            }
            else {
                $p = '<span class="badge badge bg-info"> Tidak Ada Status  </span>';
            }
            return $p;
        })
        ->addColumn('jumlah', function($row) {
            $jumlah = DB::table('table_barang_po')
                ->selectRaw('SUM(CAST(jumlah as int)) AS Total')
                ->where('id_pre_order',$row->id)
                ->first();
            return 'Rp. '.number_format($jumlah->Total,0,'.',',') ;
        })

        ->rawColumns(['aksi','status','jumlah'])

        ->make(true);
        return $dt;

    }


}

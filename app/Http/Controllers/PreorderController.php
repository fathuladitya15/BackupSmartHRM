<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use DataTables;
use Carbon\Carbon;
use App\Models\PreOrder;
use App\Models\Filemanager;
use Illuminate\Http\Request;
use App\Models\BarangPreOrder;
use Illuminate\Support\Facades\Validator;


class PreorderController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }


    function index() {
        $roles = Auth::user()->roles;

        if(in_array($roles,['admin','korlap'])) {
            $nama_bulan = [];
            for ($i = 1; $i <= 12; $i++) {
                // Membuat objek Carbon untuk tanggal tertentu
                $carbon = Carbon::create(null, $i, 1);

                // Menggunakan metode format untuk mendapatkan nama bulan
                $namaBulan = $carbon->translatedFormat('F');

                // Menambahkan nama bulan ke dalam array
                $nama_bulan[] = $namaBulan;
            }

            return view('layouts.pre_order.vAdmin',compact('nama_bulan'));
        }
        elseif ($roles == 'spv-internal') {

        }
        else {
            abort(403, "Membutuhkan Autentikasi");
        }
    }

    function create(Request $request) {
        $validator  = Validator::make($request->all(),[
            'bulan'         =>  'required',
            'no_po'         =>  'required',
            'divisi'        =>  'required',
            'tanggal'       =>  'required',
            'batas_waktu'   =>  'required',
            'diajukan_oleh' =>  'required',
        ],[
            'bulan.required'            => 'Bulan wajib diisis',
            'no_po.required'            => 'Nomor Pre Order wajib diisis',
            'divisi.required'           => 'Divis wajib diisi',
            'tanggal.required'          => 'Tanggal wajib diisi',
            'batas_waktu.required'      => 'Batas waktu wajib diisi',
            'diajukan_oleh.required'    => 'Diajukan oleh siapa wajib diisi',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }
        if($request->ttd != 1) {
            return response()->json(['status' => FALSE,'pesan' => 'Belum ditandatangani','title' => 'Opps !']);
        }

        $ttd = Filemanager::where('id_karyawan',Auth::user()->id_karyawan)->where("slug",'signature')->first();


        $dataCreate = [
            'bulan'             => $request->bulan,
            'no_po'             => $request->no_po,
            'tanggal'           => $request->tanggal,
            'divisi'            => $request->divisi,
            'batas_waktu'       => $request->batas_waktu,
            'dibuat_oleh'       => $request->diajukan_oleh,
            'ttd_pembuat'       => $ttd->path,
            'id_user'           => Auth::user()->id,
        ];

        $create_po = PreOrder::create($dataCreate);

        $tes = [];

        for ($i=0; $i < count($request->nama_barang) ; $i++) {
            BarangPreOrder::create([
                'id_pre_order' => $create_po->id,
                'nama_barang' => $request->nama_barang[$i],
                'unit' => $request->unit[$i],
                'satuan' => $request->satuan[$i],
                'biaya' => $request->biaya[$i],
                'jumlah' => $request->jumlah[$i],
                'keterangan' => $request->keterangan[$i],
            ]);
        }
        return response()->json(['status'=>TRUE,'title' => 'Sukses' ,'pesan' => 'Pemitaan telah berhasil dibuat','data' => $tes]);
    }

    function data(Request $request) {
        if (in_array(Auth::user()->roles ,['admin','korlap']) ) {
            $data = PreOrder::where('id_user',Auth::user()->id)->get();
        }else {
            $data = PreOrder::all();
        }

        $dt     = DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('aksi', function($row) {
            $edit   = '<a href="javascript:void(0)" class="btn btn-primary btn-sm" id="edit_'.$row->id.'" onclick="edit('.$row->id.')"  ><i class="bx bx-edit-alt"></i>Edit</a>';
            $hapus   = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" id="hapus_'.$row->id.'" onclick="hapus('.$row->id.')"  ><i class="bx bx-trash"></i>Hapus</a>';
            if(in_array(Auth::user()->roles,['admin','korlap'])) {
                return $edit. '&nbsp;' .$hapus;

            }else {
                if(Auth::user()->roles == 'spv-internal') {
                    if($row->status == 1) {
                        return $edit;
                    }else {
                        return "";
                    }
                }
            }
        })
        ->addColumn('status', function($row) {
            if($row->status == 0) {
                $p = '<span class="badge badge bg-warning"> Menunggu Disetujui  </span>';
            }else if($row->status == 1) {
                $p = '<span class="badge badge bg-warning"> Disetujui ( Menunggu Tandatangan Direktur )  </span>';
            }else if($row->status == 2){
                $p = '<span class="badge badge bg-danger"> Ditolak ( Direview Ulang )  </span>';
            }else if($row->status == 3){
                $p = '<span class="badge badge bg-success"> Sudah Ditandatangani  </span>';
            }else {
                $p = '<span class="badge badge bg-info"> Tidak Ada Status  </span>';
            }
            return $p;
        })

        ->rawColumns(['aksi','status'])

        ->make(true);
        return $dt;
    }

    function get_data(Request $request) {
        $data_po        = PreOrder::find($request->id);
        $data_barang_po = BarangPreOrder::where("id_pre_order",$request->id)->get();
        $total_barang   = BarangPreOrder::where("id_pre_order",$request->id)->count();

        $data  = [
            'data_po' => $data_po,
            'total_barang' => $total_barang,
            'barang_po' => view('layouts.pre_order.vBarang_PO',compact('data_barang_po'))->render(),
        ];
        return response()->json($data);
    }
}

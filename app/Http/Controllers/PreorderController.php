<?php

namespace App\Http\Controllers;

use DB;
use PDF;
use Str;
use Auth;
use DataTables;
use Carbon\Carbon;
use App\Models\Clients;
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
            return view('layouts.pre_order.vSpv');

        }
        else {
            abort(403, "Membutuhkan Autentikasi");
        }
    }

    function index_direktur($nama_client,$id) {
        return view('layouts.direktur.vPreOrder');
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
            'bulan.required'            => 'Bulan wajib diisi',
            'no_po.required'            => 'Nomor Pre Order wajib diisis',
            'divisi.required'           => 'Divisi wajib diisi',
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

        if(!$ttd) {
            return response()->json(['pesan' => 'Buat tanda tangan terlebih dahulu.'],422);
        }


        $dataCreate = [
            'bulan'             => $request->bulan,
            'no_po'             => $request->no_po,
            'tanggal'           => $request->tanggal,
            'divisi'            => $request->divisi,
            'batas_waktu'       => $request->batas_waktu,
            'dibuat_oleh'       => $request->diajukan_oleh,
            'ttd_pembuat'       => $ttd->path,
            'id_user'           => Auth::user()->id,
            'id_client'           => Auth::user()->id_client,
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
        Aktivitas(Auth::user()->name.' membuat permintaan pembelian dengan No Order:'.$create_po->no_po);
        return response()->json(['status'=>TRUE,'title' => 'Sukses' ,'pesan' => 'Pemitaan telah berhasil dibuat','data' => $tes]);
    }

    function data(Request $request) {
        if (in_array(Auth::user()->roles ,['admin','korlap']) ) {
            $data = PreOrder::where('id_user',Auth::user()->id)->get();
        }else {
            $data = PreOrder::where('id_client',Auth::user()->id_client)->get();
        }

        $dt     = DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('aksi', function($row) {
            $edit   = '<a href="javascript:void(0)" class="btn btn-primary btn-sm" id="edit_'.$row->id.'" onclick="edit('.$row->id.')"  ><i class="bx bx-edit-alt"></i>Detail</a>';
            $hapus   = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" id="hapus_'.$row->id.'" onclick="hapus('.$row->id.')"  ><i class="bx bx-trash"></i>Hapus</a>';
            $file       = '<a href="'.route('pre-order-dokumen',['id' => HashVariable($row->id)]).'" class="btn btn-primary btn-sm" id="file_'.$row->id.'" target="BLANK_"  ><i class="bx bx-download"></i>Lihat File</a>';

            if(in_array(Auth::user()->roles,['admin','korlap'])) {
                if($row->status == 2) {
                    return $file;
                }
                return $edit. '&nbsp;' .$hapus;

            }else {
                $check   = '<a href="javascript:void(0)" class="btn btn-success btn-sm" id="check_'.$row->id.'" onclick="check('.$row->id.')"  ><i class="menu-icon tf-icons bx bx-check-circle"></i>Setujui</a>';
                $reject  = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" id="reject_'.$row->id.'" onclick="reject('.$row->id.')"  ><i class="menu-icon tf-icons bx bx-x-circle"></i>Tolak</a>';
                if(Auth::user()->roles == 'spv-internal') {
                    if($row->status == 0) {
                        return $edit.'&nbsp;'.$check.'&nbsp;'.$reject;
                    }else if($row->status == 2) {
                        return $file;
                    }
                    else {
                        return "";
                    }
                }
            }
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
            }else if($row->status == 5) {
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

    function data_direktur(Request $request){
        $data  = PreOrder::where('id_client',$request->id_client)->get();
        $dt     = DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('aksi', function($row) {
            $edit       = '<a href="javascript:void(0)" class="btn btn-primary btn-sm" id="edit_'.$row->id.'" onclick="edit('.$row->id.')"  ><i class="bx bx-edit-alt"></i>Detail</a>';
            $hapus      = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" id="hapus_'.$row->id.'" onclick="hapus('.$row->id.')"  ><i class="bx bx-trash"></i>Hapus</a>';
            $check      = '<a href="javascript:void(0)" class="btn btn-success btn-sm" id="check_'.$row->id.'" onclick="check('.$row->id.')"  ><i class="menu-icon tf-icons bx bx-check-circle"></i>Setujui</a>';
            $reject     = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" id="reject_'.$row->id.'" onclick="reject('.$row->id.')"  ><i class="menu-icon tf-icons bx bx-x-circle"></i>Tolak</a>';
            $file       = '<a href="'.route('pre-order-dokumen',['id' => HashVariable($row->id)]).'" class="btn btn-primary btn-sm" id="file_'.$row->id.'" target="BLANK_"  ><i class="bx bx-download"></i>Lihat File</a>';

            if($row->status == 2) {
                return $file;
            }
            return $edit;

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

    function update(Request $request) {

        $ttd = Filemanager::where('id_karyawan',Auth::user()->id_karyawan)->where("slug",'signature')->first();

        if(in_array(Auth::user()->roles,['admin','korlap'])) {
            $data = PreOrder::find($request->id);
            $data->status = 0;
            $data->update();
            $pesan = ['status' => TRUE, 'title' => 'Berhasil perbarui data', 'pesan' => 'Data akan dikemablikan ke supervisor'];

        }
        else {
            $data = PreOrder::find($request->id);
            $data->status = $request->status;
            $data->update();
            if($request->status == 1) {
                $data->disetujui_oleh = Auth::user()->name.'(Supervisor)';
                $data->update();
                $pesan = ['status' => TRUE, 'title' => 'Berhasil disetujui', 'pesan' => 'Data akan dilanjutkan ke direktur'];
                Aktivitas(Auth::user()->name.' menyutujui permintaan pembelian dengan No Order:'.$data->no_po);
            }
            else if($request->status == 2) {
                $data->ttd_direktur = $ttd->path;
                $data->nama_direktur = Auth::user()->name;
                $data->update();
                $pesan = ['status' => TRUE, 'title' => 'Berhasil disetujui', 'pesan' => 'Data akan telah disetujui'];
                Aktivitas(Auth::user()->name.' menandatangani permintaan pembelian dengan No Order:'.$data->no_po);

            }
            elseif($request->status == 5 ) {
                $pesan = ['status' => TRUE, 'title' => 'Berhasil ditolak', 'pesan' => 'Data akan dikemablikan'];
                Aktivitas(Auth::user()->name.' menolak permintaan pembelian dengan No Order:'.$data->no_po);
            }else {
                $pesan = ['status' => FALSE, 'title' => 'Error', 'pesan' => 'Something Wrong'];
            }
        }

        return response()->json($pesan);
    }

    function dokumen($hash) {
        $id     = EncryprVariable($hash);
        $data   = PreOrder::find($id);
        $barang = BarangPreOrder::where("id_pre_order",$id)->get();
        $jumlah = DB::table('table_barang_po')
                ->selectRaw('SUM(CAST(jumlah as int)) AS Total')
                ->where('id_pre_order',$id)
                ->first();
        $tahun      = Carbon::parse($data->tanggal)->format('Y');
        $nama_pt    = Clients::where('id',$data->id_client)->first()->nama_client;
        $rupiah     = number_format($jumlah->Total,0,'.',',');
        // dd($jumlah->Total);
        $judul      = 'PURCHASE REQUISITION '. Str::upper($nama_pt).' BULAN '.Str::upper($data->bulan).' '.$tahun;
        $filename   = 'PURCHASE REQUISITION '. Str::upper($nama_pt).' BULAN '.Str::upper($data->bulan).' '.$tahun.'pdf';
        $pdf        = PDF::loadview('layouts.pdf_view.pdfPreOrder',['data' => $data,'judul' => $judul,'data_barang'=>$barang,'jumlah' => $jumlah->Total]);
        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream($filename);
    }
}













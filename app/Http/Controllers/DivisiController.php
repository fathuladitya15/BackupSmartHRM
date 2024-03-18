<?php

namespace App\Http\Controllers;


use Auth;
use DataTables;
use Carbon\Carbon;
use App\Models\Divisi;
use App\Models\Clients;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    function __construct() {

        $this->middleware('auth');
    }

    function index() {

        return view('layouts.divisi');
    }

    function data(Request $request) {
        if(Auth::user()->roles == 'superadmin') {
            $data = Divisi::where('id_client',1)->get();
        }else {
            $data = Divisi::where('id_client',Auth::user()->id_client)->get();
        }

        $dataTable = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi',function($row) {
                $hapus  = '<a href="javascript:void(0)" class="btn btn-primary btn-sm" id="edit_'.$row->id.'" onclick="edit('.$row->id.')"><i class="bx bx-edit-alt"></i>Edit</a>';
                $edit   = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="hapus('.$row->id.')"><i class="bx bxs-trash" ></i>Hapus</a>';
                return $hapus.'&nbsp;'.$edit;
            })
            ->rawColumns(['aksi'])
            ->make(true);

        return $dataTable;
    }

    function store(Request $request) {
        $nama_pt = Clients::find(Auth::user()->id_client)->nama_client;
        if($request->id == null) {
            $create = Divisi::create([
                'nama_divisi' => $request->nama_divisi,
                'id_client' => Auth::user()->id_client == null ? 1 : Auth::user()->id_client,
            ]) ;
            $pesan = ['status' => TRUE,'title' => 'Sukses !','pesan' => 'Berhasil menambahkan divisi baru.'];
            Aktivitas("Melakukan penambanan data divisi ".$request->nama_divisi." di".$nama_pt." pada tanggal ".Carbon::now()->translatedFormat("d F Y, H:I:s")."  ");

        }else {
            $update = Divisi::find($request->id);
            $update->nama_divisi = $request->nama_divisi;
            Aktivitas("Melakukan pembaruan data divisi dari ".$update->nama_divisi." menjadi ".$request->nama_divisi." di ".$nama_pt." pada tanggal ".Carbon::now()->translatedFormat("d F Y, H:I:s")."  ");
            $update->update();
            $pesan = ['status' => TRUE,'title' => 'Sukses !','pesan' => 'Berhasil memperbarui divisi.'];


        }

        return response()->json($pesan);
    }

    function get_data(Request $request) {
        $id = $request->id;
        $data = Divisi::find($id);
        return response()->json($data);
    }

    function delete_data(Request $request) {
        $nama_pt    = Clients::find(Auth::user()->id_client)->nama_client;
        $data       = Divisi::findOrFail($request->id);
        Aktivitas("Menghapus data divisi dari ".$data->nama_divisi." menjadi ".$data->nama_divisi." di ".$nama_pt." pada tanggal ".Carbon::now()->translatedFormat("d F Y, H:I:s")."  ");
        $data->delete();
        return response()->json(['status' => TRUE,'title' => 'Sukses !','pesan' => 'Berhasil menghapus divisi.']);
    }
}

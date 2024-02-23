<?php

namespace App\Http\Controllers;

use Auth;
use DataTables;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    function __construct() {
        $this->middleware('auth');
    }


    public function index() {
        return view('layouts.vShift');
    }

    function data(Request $request) {
        $id_cabang = Auth::user()->id_client;
        if(Auth::user()->role != 'superadmin') {
            $data  = Shift::where('id_client',$id_cabang)->get();
        }else {
            $data = Shift::all();
        }

        $dt = DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('type_shift', function($data) {
                $text = $data->type.' '.$data->ke;

                return $text;
            })
            ->addColumn('jam_kerja', function($data){
                $text = $data->waktu_mulai.' s/d '.$data->waktu_selesai;

                return $text;
            })
            ->addColumn('aksi', function ($data) {
                $hapus  = '<a href="javascript:void(0)" class="btn btn-primary btn-sm" id="edit_'.$data->id.'" onclick="edit('.$data->id.')"><i class="bx bx-edit-alt"></i>Edit</a>';
                $edit   = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" onclick="hapus('.$data->id.')"><i class="bx bxs-trash" ></i>Hapus</a>';
                return $hapus.'&nbsp;'.$edit;
            })
            ->rawColumns(['aksi','jam_kerja','type_shift'])
            ->make(true);

            return $dt;
    }

    function save_data(Request $request) {
        $id  = $request->id;
        $id_client = $request->id_client == null ? Auth::user()->id_client : $request->id_client;
        if($id == null) {
            Shift::create([
                'type'          => $request->nama_shift,
                'ke'            => $request->ke,
                'waktu_mulai'   => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
                'id_client'     => $id_client,
            ]);
            $pesan = ['status' => TRUE,'title' =>'Sukses' , 'pesan' => 'Shift beru telah ditambahkan'];
        }else {
            $shift              = Shift::find($id);
            $shift->type        = $request->nama_shift;
            $shift->ke          = $request->ke;
            $shift->waktu_mulai = $request->waktu_mulai;
            $shift->waktu_selesai   = $request->waktu_selesai;
            $shift->id_client   = $id_client;
            $shift->update();
            $pesan = ['status' => TRUE,'title' =>'Sukses' , 'pesan' => 'Shift telah diperbaharui'];

        }
        return response()->json($pesan);
    }

    function get_data(Request $request) {
        $id = $request->id;

        $data = Shift::find($id);

        return response()->json($data);
    }

    function delete_data(Request $request) {
        $data = Shift::findOrFail($request->id);
        $data->delete();

        return response()->json(['status' => TRUE,'title' => 'Sukses !','pesan' => 'Berhasil menghapus Shift.']);



    }
}

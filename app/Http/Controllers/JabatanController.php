<?php

namespace App\Http\Controllers;



use Auth;
use DataTables;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    function __construct() {

        $this->middleware('auth');
    }

    function index() {

        return view('layouts.jabatan');
    }

    function data(Request $request) {
        if(Auth::user()->roles == 'superadmin') {
            $data = Jabatan::where('id_client',1)->get();
        }else {
            $data = Jabatan::where('id_client',Auth::user()->id_client)->get();
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
        if($request->id == null) {
            $create = Jabatan::create([
                'nama_jabatan' => $request->nama_jabatan,
                'id_client' => Auth::user()->id_client == null ? 1 : Auth::user()->id_client,
            ]) ;
            $pesan = ['status' => TRUE,'title' => 'Sukses !','pesan' => 'Berhasil menambahkan jabatan baru.'];
        }else {
            $update = Jabatan::find($request->id);
            $update->nama_jabatan = $request->nama_jabatan;
            $update->update();
            $pesan = ['status' => TRUE,'title' => 'Sukses !','pesan' => 'Berhasil memperbarui jabatan.'];
        }

        return response()->json($pesan);
    }

    function get_data(Request $request) {
        $id = $request->id;
        $data = Jabatan::find($id);
        return response()->json($data);
    }

    function delete_data(Request $request) {
        $data = Jabatan::findOrFail($request->id);
        $data->delete();
        return response()->json(['status' => TRUE,'title' => 'Sukses !','pesan' => 'Berhasil menghapus jabatan.']);
    }


}

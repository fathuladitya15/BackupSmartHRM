<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Role;
use Illuminate\Http\Request;

class SuperadminController extends Controller
{


    function __construct() {
        $this->middleware('auth');
    }

    public function roles_index() {
        return view('layouts.superadmin.vRoles');
    }

    public function roles_data(Request $request) {
        $data = Role::all();


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

    public function roles_save(Request $request){
        if($request->id == null) {
            Role::create([
                'name_role' => $request->name_role,
                'slug_role' => $request->slug_role
            ]);
            $pesan = ['status' => TRUE,'title' => 'Sukses','pesan' => "Berhasil menambahkan role."];
        }else {
            $data = Role::find($request->id);
            $data->name_role = $request->name_role;
            $data->slug_role = $request->slug_role;
            $data->update();
            $pesan = ['status' => TRUE,'title' => 'Sukses','pesan' => "Berhasil memperbarui role."];
        }

        return response()->json($pesan);
    }

    public function roles_get(Request $request) {
        $id = $request->id;
        $data  = Role::find($id);
        return response($data);
    }

    public function roles_delete(Request $request) {

        $data = Role::findOrFail($request->id);
        $data->delete();

        return response()->json(['status' => TRUE,'title' => 'Sukses' ,'pesan' => "Hapus role berhasil."]);
    }
}

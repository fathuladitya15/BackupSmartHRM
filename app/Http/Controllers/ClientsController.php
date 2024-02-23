<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Clients;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    function __contstruct() {
        $this->middleware('auth');
    }

    function index() {
        return view('layouts.superadmin.vClient');
    }
    function data(Request $request) {
        $data = Clients::all();

        $dataTable = DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('aksi',function($row) {
            return "";
        })
        ->rawColumns(['aksi'])
        ->make(true);

        return $dataTable;
    }
    function store(Request $request) {
        return response()->json($request->all());
    }
}

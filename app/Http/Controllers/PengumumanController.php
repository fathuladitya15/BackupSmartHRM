<?php

namespace App\Http\Controllers;

use Auth;
use DataTables;
use App\Models\Pengumuman;
use App\Models\Filemanager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PengumumanController extends Controller
{
    function __contruct() {
        $this->middleware('auth');
    }

    function index() {
        return view('layouts.supervisor.vPengumuman');
    }

    function add(Request $request) {

        $validator  = Validator::make($request->all(),[
            'lampiran.*' => 'max:3072|mimes:png,jpg,jpeg,pdf'
        ],[
            'lampiran.*.max' => 'Ukuran file tidak boleh lebih dari 3 Mb',
            'lampiran.*.mimes' => 'Ekstensi file hanya .pdf .png .jpg .jpeg yang diperbolehkan',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $insert = [
            'judul'         => $request->judul,
            'keterangan'    => $request->keterangan,
            'views'         => $request->views,
            'pembuat'       => Auth::user()->id_karyawan,
        ];
        $create = Pengumuman::create($insert);


        if($request->hasfile('lampiran')) {
            $files          = $request->file('lampiran');
            $no = 1;
            foreach ($files as $file) {
                $path       = '/filemanager/lampiran_att';
                $filename   = "lampiran_".$no++."_".date("YmdHi").'.'.$file->getClientOriginalExtension();
                $loc_file    = $path.'/'.$filename;
                $file->move(public_path($path),$filename);
                Filemanager::create([
                    'filename' => $filename,
                    'path'     => $loc_file,
                    'extension' => $file->getClientOriginalExtension(),
                    'id_karyawan' => $create->id, // ID KARYAWAN DI SINI DIRUBAH FUNGSI MENJADI ID PENGUMUMAN
                    'slug'      => 'lampiran',
                    'keterangan' => 'Lampiran dari Pengumuman '.$create->keterangan,
                ]);
            }
        }


        return response()->json(['status' => TRUE,'title' => "Sukses" ,'pesan' => 'Pengumuman Berhasil dibuat !']);
    }

    function data(Request $request) {
        $data = Pengumuman::all();

        $dt =   DataTables::of($data)
                ->addIndexColumn()
                ->addColumn("aksi",function($r) {
                    return "";
                })
                ->addColumn("lampiran", function($r) {
                    $data = Filemanager::where('id_karyawan',"LIKE","%".$r->id."%")->get();
                    $tombol = "";
                    foreach ($data as $key ) {
                        $tombol .= '<button class="btn btn-primary btn-sm">file</button>&nbsp;';
                    }

                    return $tombol;
                })
                ->addColumn('views', function ($r) {
                    if($r->views == 1) {
                        $t = 'Semua Karyawan';
                    }
                    else if($r->views == 2) {
                        $t = 'Karyawan Project';
                    }
                    else if($r->views == 3) {
                        $t = 'Karyawan Pusat';
                    }
                    return $t;
                })
                ->rawColumns(['aksi','lampiran','views'])
                ->make(true);

        return $dt;
    }
}

<?php

namespace App\Http\Controllers;

use Auth;
use DataTables;
use App\Models\Pengumuman;
use App\Models\Filemanager;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


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
            'arsip'         => 0,
            'id_client'     => Auth::user()->id_client,
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
                    'keterangan' => 'Lampiran dari Pengumuman '.$create->judul,
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

                    $detail = '<a href="javascript:void(0)" class="btn btn-primary btn-sm" id="detail_'.$r->id.'" onclick="detail('.$r->id.')"  ><i class="bx bx-edit-alt"></i>Detail</a>';
                    $hapus  = '<a href="javascript:void(0)" class="btn btn-danger btn-sm" id="hapus_'.$r->id.'" onclick="hapus('.$r->id.')"  ><i class="bx bx-trash"></i>Hapus</a>';
                    $arsip  = '<a href="javascript:void(0)" class="btn btn-info btn-sm" id="arsip_'.$r->id.'" onclick="arsip('.$r->id.')"  ><i class="bx bxs-box"></i>Arsip</a>';
                    return $detail.'&nbsp;'.$hapus.'&nbsp;'.$arsip;
                })
                ->addColumn("lampiran", function($r) {
                    $data = Filemanager::where('id_karyawan',$r->id)->where('slug','lampiran')->count();

                    return $data == 0 ? 'tidak ada lampiran' : $data.' Files';
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
                ->addColumn('keterangan', function($r) {
                    if (strpos($r->keterangan, '<p>') !== false) {
                        $rs =  '"Klik detail untuk lihat lebih lanjut"';
                    } else {
                        $rs = $r->keterangan;
                    }
                    return $rs;

                })
                ->rawColumns(['aksi','lampiran','views','keterangan'])
                ->make(true);

        return $dt;
    }

    function details($var) {
        $id     = EncryprVariable($var);
        $detail = Pengumuman::find($id);
        // if($files)
        $files  = Filemanager::where('id_karyawan',$id)->get();
        // dd($files);
        return view('layouts.vDetailsPengumuman',compact('detail','files'));
    }

    function download($ency,$ext) {
        $filename   = EncryprVariable($ency);
        $file       = public_path().'/filemanager/lampiran_att/'.$filename ;

        if (file_exists($file)) {
            return Response::download($file);
        } else {
            abort(404,'File tidak tersedia');
        }
    }

    function get_data(Request $request) {
        $id     = $request->id;
        $detail = Pengumuman::find($id);
        $files  = Filemanager::where('id_karyawan',$id)->get();

        return response()->json(['data' => $detail,'lampiran' => $files]);
    }

    function get_files(Request $request) {
        $id     = $request->id;
        $files  = Filemanager::find($id);
        return response()->json($files);
    }

    function delete_files(Request $request) {
        $id = $request->id;
        $data  = Filemanager::findOrfail($id);

        $filePath  = public_path($data->path);

        $filename = public_path("/filemanager/lampiran_att/lampiran_1_202403191544_ opy.jpeg"); // test link hapus
        if(file_exists($filePath)) {
            unlink($filePath);
            $data->delete();
            $r = ['status' => TRUE,'pesan' => 'File berhasil dihapus','title' => 'sukses'];
        }else {
            $r = ['status' => FALSE,'pesan' => 'File gagal dihapus','title' => 'Peringatan'];
        }
        return response()->json($r);
    }

    function delete_pengumuman(Request $request) {
        $id     = $request->id;
        $data   = Pengumuman::findOrFail($id);


        $files = Filemanager::where('id_karyawan',$id)->get();

        if($files) {
            foreach ($files as $key ) {
                $filePath = public_path($key->path);
                unlink($filePath);
                $hapus = Filemanager::findOrFail($key->id);
                $hapus->delete();
            }

        }

        $data->delete();

        return response()->json(['status' => TRUE,'pesan' => 'Pengumuman berhasil dihapus','title' => 'Sukses']);
    }
}

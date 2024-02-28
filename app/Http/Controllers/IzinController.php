<?php

namespace App\Http\Controllers;

use PDF;
use Auth;
use Validator;
use App\Models\Izin;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Filemanager;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    function __contstruct() {
        $this->middleware('auth');
    }


    function index() {
        $id_karyawan    = Auth::user()->id_karyawan;
        $role           = Auth::user()->roles;
        $detail         = Karyawan::where('id_karyawan',Auth::user()->id_karyawan)->first();
        $divisi         = Divisi::find($detail->divisi);
        $jabatan        = Jabatan::find($detail->jabatan);

        if($detail->lokasi_kerja != 3) {
            if (in_array($role,['admin','korlap'])){
                return view('layouts.admin_korlap.vIzinDefault');
            }
            elseif($role == 'karyawan') {
                return view('layouts.izin.vIzinDefault',compact('detail','jabatan','divisi'));
            }else {
                dd($role);
            }
        }else {

            dd($role);
        }



    }


    function saving(Request $request) {
        $id_izin    = $request->id_izin;
        $ttdCreate  = Filemanager::where("id_karyawan",Auth::user()->id_karyawan)->where('slug','signature')->first();

        if($id_izin == null) {
            if(Auth::user()->roles == 'karyawan' || Auth::user()->id_client != 3) {

                $createData = [
                    'karyawan_id'           => $request->id_karyawan,
                    'nama_karyawan'         => $request->nama_karyawan,
                    'divisi'                => $request->divisi,
                    'jabatan'               => $request->jabatan,
                    'detail'                => $request->detail,
                    'alasan'                => $request->alasan,
                    'tanggal_pembuatan'     => $request->tanggal,
                    'jam_keluar'            => $request->waktu,
                    'ttd_karyawan'          => $ttdCreate->path,
                    'kembali'               => $request->kembali,
                    'status'                => 0,
                    'id_client'             => Auth::user()->id_client,
                ];

                $pesan = ['status' => TRUE, 'title' => 'Sukses' ,'pesan' => 'Berhasil membuat izin keluar'];
            }
            $create = Izin::create($createData);

        }else {
            if(in_array(Auth::user()->roles,['admin','korlap'])){
                if(Auth::user()->id_client != 3){
                    $data = Izin::find($id_izin);
                    $data->ttd_mengetahui = $ttdCreate->path;
                    $data->no_surat = $request->no_surat;
                    $data->status = 1;
                    $data->user_id_mengetahui = Auth::user()->name;
                    $data->update();
                    $pesan = ['status' => TRUE, 'title' => 'Sukses' ,'pesan' => 'Permintaan izin '.$data->nama_karyawan.' telah disetujui'];

                }
            }
        }



        return response()->json($pesan);
    }

    function detail(Request $request) {

        $data = Izin::find($request->id);

        if($data) {
            $pesan = ['status' => TRUE,'data' => $data];
        }else {
            $pesan = ['status' => FALSE];
        }
        return response()->json($pesan);

    }

    function download_file($hash){
        $id = EncryprVariable($hash);
        $data = Izin::find($id);


        if(Auth::user()->id_client != 3) {
            $id_kr          = Filemanager::where('path',$data->ttd_karyawan)->where("slug",'signature')->first()->id_karyawan;
            $id_admin       = Filemanager::where('path',$data->ttd_mengetahui)->where("slug",'signature')->first()->id_karyawan;
            $filename = 'Detail Izin Keluar '.$data->nama_karyawan;
            $pdf            = PDF::loadview("layouts.pdf_view.pdfIzinDefault",['data' => $data]);

        }

        return $pdf->stream($filename.'.pdf');
        // dd($data);
    }
 }

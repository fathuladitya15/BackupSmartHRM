<?php

namespace App\Http\Controllers;

use PDF;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Clients;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Peringatan;
use Illuminate\Http\Request;

class PeringatanController extends Controller
{
    function __contruct() {
        $this->middleware('auth');
    }

    function index() {
        $karyawan   = User::where('id_client',Auth::user()->id_client)->where('roles','karyawan')->get();
        $role       = Auth::user()->roles;

        // dd($role);

        if(in_array($role,['admin','korlap'])){
            return view("layouts.admin_korlap.vSPadmin",compact("karyawan"));
        }else if($role == 'spv-internal') {
            $total_surat  = peringatan::where('status','!=',0)->where('lokasi_kerja',Auth::user()->id_client)->count();
            $total_menuggu = peringatan::where('status',1)->where('lokasi_kerja',Auth::user()->id_client)->count();
            return view("layouts.supervisor.vPeringatan",compact("karyawan",'total_surat','total_menuggu'));
        }else if($role == 'karyawan'){
            return view('layouts.karyawan.vPeringatan');
        }
        else {
            dd($role);
        }

    }

    function data_karyawan(Request $request) {
        $karyawan   =   Karyawan::where('id_karyawan',$request->id)->first();
        $jabatan    =   Jabatan::find($karyawan->jabatan)->nama_jabatan;
        $lokasi_    =   Clients::find($karyawan->lokasi_kerja)->nama_client;
        return response()->json(['jabatan' => $jabatan,'lokasi_kerja' => $lokasi_,'karyawan' => $karyawan]);
    }

    function save(Request $request) {
        $roles      = Auth::user()->roles;
        $id_client  = Auth::user()->id_client;
        if($request->ttd == 1 ){
            if(in_array($roles,['admin','korlap'])){
                $ms     = 90;
                $masa_berlaku  = Carbon::parse($request->mulai_dari);
                $sampai_dengan = Carbon::parse($request->sampai_dengan);
                $cek_masa_berlaku  = $masa_berlaku->diffInDays($sampai_dengan);
                $tanggal_ms     = Carbon::parse($masa_berlaku->addDays($ms))->translatedFormat('d M Y') ;
                $keterangan     = "Peringatan ini berlaku selama 3 bulan, ".Carbon::parse($request->mulai_dari)->translatedFormat("d F Y")." s/d ".$sampai_dengan->translatedFormat('d F Y')." . Apabila Mengulangi Pelanggaran Yang Sama Dalam Kurun Waktu Sama Berlakunya Sanksi Disiplin, Akan Dikenakan Sanksi Lebih Tinggi Dari Sebelumnya (Sesuai Peraturan PT Proven Force Indonseia)";
                if($cek_masa_berlaku < $ms ){
                    $pesan = ['status'=> FALSE,'title' => 'Cek Masa Berlaku','pesan' => 'Masa berlaku surat harus '.$tanggal_ms.''];
                }else if($cek_masa_berlaku > $ms ){
                    $pesan = ['status'=> FALSE,'title' => 'Cek Masa Berlaku','pesan' => 'Masa berlaku surat harus'.$tanggal_ms.''];
                }else {
                    $nama_karyawan  = User::where('id_karyawan',$request->id_karyawan)->first()->name;
                    $detail         = Karyawan::where('id_karyawan',$request->id_karyawan)->first();
                    $divisi         = Divisi::find($detail->divisi)->nama_divisi;
                    $jabatan        = Jabatan::find($detail->jabatan)->nama_jabatan;
                    $data = [
                        'no_surat'              => $request->no_sp,
                        'karyawan_id'           => $request->id_karyawan,
                        'nama_karyawan'         => $nama_karyawan,
                        'jabatan'               => $jabatan,
                        'divisi'                => $divisi,
                        'lokasi_kerja'          => $detail->lokasi_kerja,
                        'karyawan_id_pembuat'   => Auth::user()->id_karyawan,
                        'tipe_peringatan'       => $request->tipe_peringatan,
                        'alasan'                => $request->alasan_peringatan,
                        'keterangan'            => $keterangan,
                        'tanggal_pembuatan'     => Carbon::now()->format('d-m-Y'),
                        'tanggal_berlaku'       => $request->mulai_dari,
                        'tanggal_kadaluarsa'    => $request->sampai_dengan,
                        'nama_pemberi_sp'       => $request->nama_pemberi,
                        'status'                => 0,
                    ];
                    Peringatan::create($data);
                    $pesan = ['status' => TRUE,'title' => "Sukses",'pesan' => 'Surat peringatan untuk '.$nama_karyawan.' berhasil dibuat' ,'data' => $data ];
                }
            }else if($roles == 'spv-internal'){
                return $this->update($request);
            }

        }
        else {
            $pesan = ['status' => FALSE,'title' => 'Tandatangi Surat','pesan' => 'Anda belum mentandatangani surat ini'];
        }
        return response()->json($pesan);
    }

    function details(Request $request) {
        $data = Peringatan::find($request->id);
        $lokasi_kerja = Clients::find($data->lokasi_kerja)->nama_client;

        return response()->json(['status' => TRUE,'data'=>$data ,'lokasi_kerja' => $lokasi_kerja]);
    }

    function update(Request $request) {
        $role = Auth::user()->roles;
        $data = Peringatan::find($request->id);
        if(in_array($role,['admin','korlap'])){
            if($data->status == 0){
                $data->status = 1;
                $data->update();
            }else {
                $data->status = $request->status;
                $data->update();
            }
            $pesan = ['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Data berhasil dikirimkan '];
        }else if($role == 'spv-internal'){
            $data->status = 2;
            $data->disetujui_pada = Carbon::now()->format('d m Y');
            $data->disetujui_oleh = Auth::user()->name;
            $data->update();
            $pesan = ['status' => TRUE,'title' => 'Sukses' ,'pesan' => 'Data telah disetujui '];
        }
        else {
            $pesan = ['status' => FALSE, 'title' => 'Gagal', 'pesan' => "Data tidak terkirim"];
            // $pesan = ['status' => FALSE,'title' => "Error" ,'Pesan' => "TES" , 'data' => $request->all()];

        }
        return response()->json($pesan);
    }

    function files($id) {
        $data  = Peringatan::find($id);
        $lokasi_kerja = Clients::find($data->lokasi_kerja)->nama_client;
        $filename = "Surat Peringatan ".$data->nama_karyawan;
        // view("")
        $pdf = PDF::loadView('layouts.pdf_view.pdfSuratPeringatan',['data' => $data,'lokasi_kerja'=>$lokasi_kerja]);
        $pdf->setPaper('A4','potrait');
        return $pdf->stream($filename.'.pdf');
        dd($id);
    }
}

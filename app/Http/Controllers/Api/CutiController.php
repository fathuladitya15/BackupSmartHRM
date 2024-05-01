<?php

namespace App\Http\Controllers\Api;

use Auth;
use DB;
use PDF;
use Carbon\Carbon;
use App\Models\Cuti;
use App\Models\LogCuti;
use App\Models\Filemanager;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Clients;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\KategoriCuti;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CutiController extends Controller
{
    function get_kategori_cuti(){
        $kategori_cuti = KategoriCuti::all();
        return response()->json(['status' => 200,'data' => $kategori_cuti ]);
    }

    function get_data_cuti(Request $request) {
        $tahun      = Carbon::now()->format('Y');
        $data       = Cuti::where('id_karyawan',$request->id_karyawan)->get();
        $totalCuti  = LogCuti::where('id_karyawan',$request['id_karyawan'])->where('tahun',$tahun)->sum('cuti_get');
        $batas_hari =  12 - $totalCuti;
        $result = [];
        foreach ($data as $key) {
            $result[] = [
                'id'    => $key['id'],
                'id_karyawan'       => $key['id_karyawan'],
                'nama_karyawan'     => $key['nama_karyawan'],
                'divisi'            => $key['divisi'],
                'jabatan'           => $key['jabatan'],
                'start_date'        => Carbon::parse($key['start_date'])->translatedFormat('l, d F Y'),
                'end_date'          => Carbon::parse($key['end_date'])->translatedFormat('l, d F Y'),
                'alasan'            => $key['alasan'],
                'kategori_cuti'     => $key['kategori_cuti'],
                'tanggal_pengajuan' => Carbon::parse($key['created_at'])->translatedFormat('l, d F Y'),
                'cuti'              => $key['ambil_cuti'],
                'info'              => $this->info_status($key['status']),
                'sisa_cuti'         => $batas_hari,
                'disetujui_oleh'    => $key['disetujui_oleh'],
                'detail'            => $this->detail_data($key['id']),
            ];
        }
        if($data->count() > 0){
            return response()->json(['status' => 200,'data' => $result]);
        }
        return response()->json(['data' => $result]);
    }

    function create_cuti_internal(Request $request)  {
        $tipeKaryawan           = $this->tipeKaryawan($request->id_karyawan);
        $validasi_tanggal       = $this->validasi_tanggal_cuti($request->all());

        if($validasi_tanggal['status'] != 200) {
            return response()->json(['status' => 404,'pesan' => $validasi_tanggal['pesan'],'total Cuti' => $validasi_tanggal['totalCuti']]);
        }
        if(in_array($tipeKaryawan['karyawanType'],['kr-project','kr-pusat','manajer'])) {
            $getDataKaryawan    = Karyawan::where('id_karyawan',$request->id_karyawan)->first();
            $divisi             = Divisi::find($getDataKaryawan->divisi)->nama_divisi;
            $jabatan            = Jabatan::find($getDataKaryawan->jabatan)->nama_jabatan;
            $namaClient         = Clients::find($getDataKaryawan->lokasi_kerja)->nama_client;

            $cek_TTD            = $this->cek_ttd($request->id_karyawan);
            if($cek_TTD == FALSE) {
                $pesan  = 'Tanda tangan belum dibuat, default tandatangan 1';
                $r      = 1;
            }else {
                $ttd    = Filemanager::where("id_karyawan",$request->id_karyawan)->where("slug",'signature')->first();
                $pesan  = 'Tanda tangan tersedia';
                $r      = $ttd->path;
            }
            $cutiCreate = [
                'id_karyawan'   => $request->id_karyawan,
                'nama_karyawan' => $getDataKaryawan->nama_karyawan,
                'divisi'        => $divisi,
                'jabatan'       => $jabatan,
                'kategori_cuti' => $request->kategori_cuti,
                'alasan'        => $request->alasan_cuti,
                'ambil_cuti'    => $validasi_tanggal['cuti_yg_diambil'],
                'jumlah_cuti'   => $validasi_tanggal['sisa_cuti'],
                'ttd_karyawan'  => $r,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'status'        => $jabatan == 'Manager' ? 2 : 0,
            ];
            Cuti::create($cutiCreate);

            $logCutiCreate = [
                'id_karyawan' => $request->id_karyawan,
                'total_hari'  => $validasi_tanggal['sisa_cuti'],
                'cuti_get'    => $validasi_tanggal['cuti_yg_diambil'],
                'keterangan_cuti' => $request->kategori_cuti,
                'tahun'       => Carbon::parse($request->start_date)->format("Y")  ,
            ];
            LogCuti::create($logCutiCreate);

            return response()->json(['pesan' => 'Cuti dengan ID Karyawan : '.$request->id_karyawan.' berhasil dibuat','ttd' => $pesan],200);
        }else {
           return response()->json(['pesan' => 'Buat Cuti Gagal'],404);
        }
    }

    function validasi_tanggal_cuti($request) {

        $tahunSkarang   = Carbon::now()->format('Y');
        $totalCuti = LogCuti::where('id_karyawan',$request['id_karyawan'])->where('tahun',$tahunSkarang)->sum('cuti_get');

        $batas_hari     =  12 - $totalCuti;

        $start_date_new = Carbon::parse($request['start_date']);
        $end_date_new   = Carbon::parse($request['end_date']);


        // SABTU MINGGU TIDAK DI HITUNG
        $not_work_hours = $start_date_new->diffInDaysFiltered(function(Carbon $date) {
            return $date->isWeekend();
        }, $end_date_new);

        //PERHITUNGAN TANGGAL
        $all_days       = $start_date_new->diffInDays($end_date_new) + 1;

        // TOTAL CUTI
        $total_cuti = $all_days - $not_work_hours;


        if($total_cuti > $batas_hari) {
            $status = ['status' => 404, 'pesan' => 'Total Cuti yang tersisa '.$batas_hari.' Hari','totalCuti' => $totalCuti];
        }else {

            $status = ['status' => 200 ,'pesan' => 'Cuti tervalidasi', 'cuti_yg_diambil' => $total_cuti,'sisa_cuti' => $batas_hari] ;

        }
        return $status;

    }

    function get_data_cuti_manager(Request $request) {
        $roles      = User::where('id_karyawan',$request->id_karyawan)->first()->roles;
        $divisi     = Karyawan::where('id_karyawan',$request->id_karyawan)->with('divisi')->first()->divisi()->first()->nama_divisi;

        $data       = Cuti::where('divisi',$divisi)->where('status','>=',0)->get();

        $result = [];
        foreach ($data as $key) {
            $result[] = [
                'id'=> $key['id'],
                'id_karyawan'       => $key['id_karyawan'],
                'nama_karyawan'     => $key['nama_karyawan'],
                'divisi'            => $key['divisi'],
                'jabatan'           => $key['jabatan'],
                'start_date'        => Carbon::parse($key['start_date'])->translatedFormat('l, d F Y'),
                'end_date'          => Carbon::parse($key['end_date'])->translatedFormat('l, d F Y'),
                'alasan'            => $key['alasan'],
                'kategori_cuti'     => $key['kategori_cuti'],
                'tanggal_pengajuan' => Carbon::parse($key['created_at'])->translatedFormat('l, d F Y'),
                'cuti'              => $key['ambil_cuti'],
                'info'              => $this->info_status($key['status']),
                'disetujui_oleh'    => $key['disetujui_oleh'],

                'detail'            => $this->detail_data($key['id']),

            ];
        }
        if ($roles) {
            if($data) {
                return response()->json(['status' => 200,'data' => $result,'pesan' => 'Data ditemukan','divisi' => $divisi]);
            }
        }
        return response()->json(['status' => 404, 'pesan' => 'Data tidak ditemukan']);
    }

    function get_data_cuti_spv_hrd(Request $request) {
        $data       = Cuti::where('status','>=',1)->get();

        $result = [];
        foreach ($data as $key) {
            $result[] = [
                'id'=> $key['id'],

                'id_karyawan'       => $key['id_karyawan'],
                'nama_karyawan'     => $key['nama_karyawan'],
                'divisi'            => $key['divisi'],
                'jabatan'           => $key['jabatan'],
                'start_date'        => Carbon::parse($key['start_date'])->translatedFormat('l, d F Y'),
                'end_date'          => Carbon::parse($key['end_date'])->translatedFormat('l, d F Y'),
                'alasan'            => $key['alasan'],
                'kategori_cuti'     => $key['kategori_cuti'],
                'tanggal_pengajuan' => Carbon::parse($key['created_at'])->translatedFormat('l, d F Y'),
                'cuti'              => $key['ambil_cuti'],
                'info'              => $this->info_status($key['status']),
                'disetujui_oleh'    => $key['disetujui_oleh'],
                'detail'            => $this->detail_data($key['id']),


            ];
        }

        if($data->count() > 0) {
            return response()->json(['status' => 200,'pesan' => 'Data ditemukan', 'data' => $result]);
        }

        return response()->json(['status' => 404 ,'pesan' => 'Data tidak ditemukan']);
    }

    function get_data_cuti_dir_hrd(Request $request) {
        $data       = Cuti::where('status','>=',2)->get();

        $result = [];
        foreach ($data as $key) {
            $result[] = [
                'id'=> $key['id'],

                'id_karyawan'       => $key['id_karyawan'],
                'nama_karyawan'     => $key['nama_karyawan'],
                'divisi'            => $key['divisi'],
                'jabatan'           => $key['jabatan'],
                'start_date'        => Carbon::parse($key['start_date'])->translatedFormat('l, d F Y'),
                'end_date'          => Carbon::parse($key['end_date'])->translatedFormat('l, d F Y'),
                'alasan'            => $key['alasan'],
                'kategori_cuti'     => $key['kategori_cuti'],
                'tanggal_pengajuan' => Carbon::parse($key['created_at'])->translatedFormat('l, d F Y'),
                'cuti'              => $key['ambil_cuti'],
                'info'              => $this->info_status($key['status']),
                'disetujui_oleh'    => $key['disetujui_oleh'],
                'status_pengajuan'  => $key['status'],
            ];
        }

        if($request->id_karyawan) {
            if($data->count() > 0) {
                $id_divisi = Karyawan::where('id_karyawan',$request->id_karyawan)->first()->divisi;
                $divisi = Divisi::find($id_divisi);
                if($divisi->nama_divisi == 'MPO'){
                    $roles      = User::where('id_karyawan',$request->id_karyawan)->first()->roles;
                    $divisi     = Karyawan::where('id_karyawan',$request->id_karyawan)->with('divisi')->first()->divisi()->first()->nama_divisi;

                    $data       = Cuti::where('divisi',$divisi)->where('status','>=',0)->get();

                    $result = [];
                    foreach ($data as $key) {
                        $result[] = [
                            'id'=> $key['id'],
                            'id_karyawan'       => $key['id_karyawan'],
                            'nama_karyawan'     => $key['nama_karyawan'],
                            'divisi'            => $key['divisi'],
                            'jabatan'           => $key['jabatan'],
                            'start_date'        => Carbon::parse($key['start_date'])->translatedFormat('l, d F Y'),
                            'end_date'          => Carbon::parse($key['end_date'])->translatedFormat('l, d F Y'),
                            'alasan'            => $key['alasan'],
                            'kategori_cuti'     => $key['kategori_cuti'],
                            'tanggal_pengajuan' => Carbon::parse($key['created_at'])->translatedFormat('l, d F Y'),
                            'cuti'              => $key['ambil_cuti'],
                            'disetujui_oleh'    => $key['disetujui_oleh'],
                            'info'              => $this->info_status($key['status'])

                        ];
                    }
                    if ($roles) {
                        if($data) {
                            return response()->json(['status' => 200,'data' => $result,'pesan' => 'Data ditemukan','divisi' => $divisi]);
                        }
                    }
                    return response()->json(['status' => 404, 'pesan' => 'Data tidak ditemukan']);
                }else {
                    return response()->json(['status' => 200,'pesan' => 'Data ditemukan', 'data' => $result]);
                }
            }else {
                return response()->json(['status' => 404 ,'pesan' => 'Data tidak ditemukan']);
            }
        }else {
            return response()->json(['status' => 404, 'pesan' => 'ID Karyawan dibutuhkan']);
        }

    }

    function update_status(Request $request) {
        $roles     = User::where('id_karyawan',$request->id_karyawan)->first()->roles;
        // return response()->json($roles);

        $dataCuti   = Cuti::find($request->id);
        $cek_TTD    = $this->cek_ttd($request->id_karyawan);
        if($cek_TTD == FALSE) {
            return redirect()->route('create-tanda-tangan-m',['id_karyawan' => $request->id_karyawan]);
        }

        if($roles == 'manajer') {
            $update = Cuti::find($request->id);
            $update->status = 1;
            $update->ttd_manager =  $cek_TTD['path'];
            $update->update();
            $pesan              = ['pesan' => 'Cuti ID Karyawan: '.$dataCuti->id_karyawan. ' Telah ditandatangani Manager Divisi'];


        }else if($roles == 'hrd') {
            $update = Cuti::find($request->id);
            $update->status = 2;
            $update->ttd_hrd =  $cek_TTD['path'];
            $update->update();
            $pesan              = ['pesan' => 'Cuti ID Karyawan: '.$dataCuti->id_karyawan. ' Telah ditandatangani Supervisor HRD'];

        }else if($roles == 'direktur') {
            $divisi = Karyawan::where('id_karyawan',$request->id_karyawan)->with('divisi')->first()->divisi()->first()->nama_divisi;

            if($divisi == 'MPO') {
                $update = Cuti::find($request->id);
                $update->status = 1;
                $update->ttd_manager =  $cek_TTD['path'];
                $update->update();
                $pesan              = ['pesan' => 'Cuti ID Karyawan: '.$dataCuti->id_karyawan. ' Telah ditandatangani Manager Divisi'];
            }else {
                if($dataCuti->status == 3) {
                    $update = Cuti::find($request->id);
                    $update->status = 4;
                    $update->disetujui_oleh = 'Direktur HRD';
                    $update->disetujui_pada = Carbon::now()->translatedFormat('d F Y');
                    $update->update();
                    $pesan              = ['pesan' => 'Cuti ID Karyawan: '.$dataCuti->id_karyawan. ' Telah disetujui Direktur HRD'];

                }else {
                    $update = Cuti::find($request->id);
                    $update->status = 3;
                    $update->ttd_direktur =  $cek_TTD['path'];
                    $update->update();
                    $pesan              = ['pesan' => 'Cuti ID Karyawan: '.$dataCuti->id_karyawan. ' Telah ditandatangani Direktur HRD'];

                }
            }
        }else {
            return response()->json(['status' => 404,'pesan' => 'Terjadi kesalahan server' ]);
        }
        return response()->json($pesan,200);

    }

    function info_status($status) {

        if($status == 0) {
            $s = 'Menunggu Ditanda Tangani Manager Divisi';
        }else if($status == 1) {
            $s = 'Menunggu Ditanda Tangani HRD';
        }else if($status == 2) {
            $s = 'Menunggu Ditanda Tangani Direktur HRD';
        }else if ($status == 3) {
            $s = 'Menunggu Disetujui Direktur HRD';
        }else if($status == 4) {
            $s = 'Sudah Ditanda Tangani ';

        }
        else {
            $s = 'Status tidak diketahui';
        }

        return $s;

    }

    function cek_ttd($id_karyawan) {
        $cek = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','signature')->count();
        if($cek > 0) {
            $datas = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','signature')->first();

            $data = [
                'path' =>  $datas->path
            ];

            return $data;
        }

        return FALSE;
    }

    function detail_data($id) {
        $data = Cuti::find($id);

        $result = [
            'id_karyawan'           => $data->id_karyawan,
            'nama_karyawan'         => $data->nama_karyawan,
            'divisi'                => $data->divisi,
            'jabatan'               => $data->jabatan,
            'start_date'            => Carbon::parse($data->start_date)->translatedFormat('l, d F Y'),
            'end_date'              => Carbon::parse($data->end_date)->translatedFormat('l, d F Y'),
            'alasan'                => $data->alasan,
            'kategori_cuti'         => $data->kategori_cuti,
            'tanggal_pengajuan'     => Carbon::parse($data->created_at)->translatedFormat('l, d F Y'),
            'cuti'                  => $data->ambil_cuti,
            'disetujui_oleh'        => $data->disetujui_oleh,
            'ttd_karyawan'          => $data->ttd_karyawan == null ? "" : asset($data->ttd_karyawan),
            'ttd_manager'           => $data->ttd_manager == null ? "" : asset($data->ttd_manager),
            'ttd_spv_hrd'           => $data->ttd_hrd == null ? "" : asset($data->ttd_hrd),
            'ttd_dir_hrd'           => $data->ttd_direktur == null ? "" : asset($data->ttd_direktur),
        ];
        return $result;
    }

    function tipeKaryawan($id) {
        $data   = Karyawan::where('id_karyawan',$id)->first();
        $user   = User::where('id_karyawan',$id)->first();
        $result = [
            'kategori'      => $data->kategori,
            'karyawanType'  => $user->roles,
        ];
        return $result;
    }

    function viewFile($id) {
        $data       = Cuti::where('id',$id)->first();
        // $filename   = 'Form Cuti '.$data->nama_karyawan;
        // $jabatan    = Karyawan::where('id_karyawan',$data->id_karyawan)->with('jabatan')->first()->jabatan()->first()->nama_jabatan;
        // $pdf        = PDF::loadview("layouts.pdf_view.pdfFormCuti",['data' => $data,'filename'=> $filename,'jabatan' => $jabatan]);
        // $pdf->setPaper('A4', 'landscape');
        // return $pdf->stream($filename.'.pdf');
        return response()->json(['data' => $data]);
    }
}

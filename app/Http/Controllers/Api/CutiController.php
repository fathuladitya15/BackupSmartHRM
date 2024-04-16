<?php

namespace App\Http\Controllers\Api;

use Auth;
use DB;
use Carbon\Carbon;
use App\Models\Cuti;
use App\Models\LogCuti;
use App\Models\Filemanager;
use App\Models\Divisi;
use App\Models\Karyawan;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CutiController extends Controller
{
    function get_sisa_cuti($id_karyawan){

    }

    function get_data_cuti(Request $request) {
        $tahun = Carbon::now()->format('Y');
        $data = Cuti::where('id_karyawan',$request->id_karyawan)->get();
        $totalCuti = LogCuti::where('id_karyawan',$request['id_karyawan'])->where('tahun',$tahun)->sum('cuti_get');
        // $query = "SELECT SUM(CAST(cuti_get AS INT)) as cuti_yg_diambil FROM table_log_cuti WHERE id_karyawan= '$request[id_karyawan]' AND tahun = '$tahun'";
        // $result = DB::select($query);
        // $totalCuti = $result[0]->cuti_yg_diambil;
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
                'info'              => $this->info_status($key['status'])
            ];
        }
        if($data->count() > 0){
            return response()->json(['status' => 200,'data' => $result,'sisa_cuti' => $batas_hari]);
        }
        return response()->json(['status' => 404,'pesan' => 'Data tidak tersedia']);
    }

    function create_cuti_internal(Request $request)  {
        $roles = User::where('id_karyawan',$request->id_karyawan)->first()->roles;
        $validasi_tanggal = $this->validasi_tanggal_cuti($request->all());
        if($validasi_tanggal['status'] != 200) {
            return response()->json(['status' => 404,'pesan' => $validasi_tanggal['pesan'],'total Cuti' => $validasi_tanggal['totalCuti']]);
        }
        if(in_array($roles,['kr-project','kr-pusat','manajer'])) {

            $jabatan = Karyawan::where('id_karyawan',$request->id_karyawan)->with('jabatan')->first()->jabatan()->first()->nama_jabatan;
            $ttd  = Filemanager::where("id_karyawan",$request->id_karyawan)->where("slug",'signature')->first();
            $cutiCreate = [
                'id_karyawan'   => $request->id_karyawan,
                'nama_karyawan' => $request->nama_karyawan,
                'divisi'        => $request->divisi,
                'jabatan'       => $request->jabatan,
                'kategori_cuti' => $request->kategori_cuti,
                'alasan'        => $request->alasan_cuti,
                'ambil_cuti'    => $validasi_tanggal['cuti_yg_diambil'],
                'jumlah_cuti'   => $validasi_tanggal['sisa_cuti'],
                'ttd_karyawan'  => 1,
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

            $pesan = ['status' => 200,'title' => 'Sukses ', 'pesan' => 'Cuti dengan ID Karyawan : '.$request->id_karyawan.' berhasil ditambahkan'];

        }else {
            $pesan = ['status' => 500 ,'pesan' => 'Server Error '];
        }
        return response()->json($pesan);
    }

    function validasi_tanggal_cuti($request) {

        $tahunSkarang   = Carbon::now()->format('Y');
        $totalCuti = LogCuti::where('id_karyawan',$request['id_karyawan'])->where('tahun',$tahunSkarang)->sum('cuti_get');
        // $query          = "SELECT SUM(CAST(cuti_get AS INT)) as cuti_yg_diambil FROM table_log_cuti WHERE id_karyawan= '$request[id_karyawan]' AND tahun = '$tahunSkarang'";
        // $result         = DB::select($query);
        // $totalCuti      = $result[0]->cuti_yg_diambil;

        $batas_hari     =  12 - $totalCuti;

        $start_date_new = Carbon::parse($request['start_date']);
        $end_date_new   = Carbon::parse($request['end_date']);

        // return response()->json($start_date_new->format('d M Y'));

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
                'info'              => $this->info_status($key['status'])

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
                'info'              => $this->info_status($key['status'])

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
        $roles = User::where('id_karyawan',$request->id_karyawan)->first()->roles;
        // return response()->json($roles);

        $dataCuti = Cuti::find($request->id);

        if($roles == 'manajer') {
            $update = Cuti::find($request->id);
            $update->status = 1;
            $update->ttd_manager = 1;
            $update->update();

        }else if($roles == 'hrd') {
            $update = Cuti::find($request->id);
            $update->status = 2;
            $update->ttd_hrd = 1;
            $update->update();
        }else if($roles == 'direktur') {
            $divisi = Karyawan::where('id_karyawan',$request->id_karyawan)->with('divisi')->first()->divisi()->first()->nama_divisi;

            if($divisi == 'MPO') {
                $update = Cuti::find($request->id);
                $update->status = 1;
                $update->ttd_manager = 1;
                $update->update();
            }else {
                // $StatusCuti = Cuti::find($request->id);
                if($dataCuti->status == 3) {
                    $update = Cuti::find($request->id);
                    $update->status = 4;
                    // $update->ttd_direktur = 1;
                    $update->update();
                }else {
                    $update = Cuti::find($request->id);
                    $update->status = 3;
                    $update->ttd_direktur = 1;
                    $update->update();

                }
            }
        }else {
            return response()->json(['status' => 404,'pesan' => 'Terjadi kesalahan server' ]);
        }
        return response()->json(['status' => 200,'pesan' => 'Cuti '.$dataCuti->nama_karyawan.' telah diperbaharui' ]);

    }
    function info_status($status) {

        if($status == 0) {
            $s = 'Menunggu ditandatangani Manager Divisi';
        }else if($status == 1) {
            $s = 'Menunggu ditandatangani HRD';
        }else if($status == 2) {
            $s = 'Menunggu ditandatangani Direktur HRD';
        }else if ($status == 3) {
            $s = 'Menunggu disetujui Direktur HRD';
        }else if($status == 4) {
            $s = 'Cuti telah disetjui Direktur HRD';
        }
        else {
            $s = 'Status tidak diketahui';
        }

        return $s;

    }
}

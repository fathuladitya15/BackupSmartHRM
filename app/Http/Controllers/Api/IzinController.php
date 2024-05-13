<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Izin;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Clients;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Filemanager;
use Carbon\Carbon;
use PDF;

class IzinController extends Controller
{

    function create_izin(Request $request) {

        $tipeKaryawan   = $this->tipeKaryawan($request->id_karyawan);
        $cek_TTD        = $this->cek_ttd($request->id_karyawan);

        if($cek_TTD == FALSE) {
            $pesan  = 'Tanda tangan belum dibuat, default tandatangan 1';
            $r      = 1;
        }else {
            $ttd    = Filemanager::where("id_karyawan",$request->id_karyawan)->where("slug",'signature')->first();
            $pesan  = 'Tanda tangan tersedia';
            $r      = $ttd->path;
        }
        
        if($request->ttd == 0 || $request->ttd == null) {
            return response()->json(['pesan' => 'Dokumen belum ditandatangani'],404);
        }
        if(in_array($tipeKaryawan['karyawanType'],['kr-project','kr-pusat','manajer'])) {
            $getDataKaryawan    = Karyawan::where('id_karyawan',$request->id_karyawan)->first();
            $divisi             = Divisi::find($getDataKaryawan->divisi)->nama_divisi;
            $jabatan            = Jabatan::find($getDataKaryawan->jabatan)->nama_jabatan;
            $namaClient         = Clients::find($getDataKaryawan->lokasi_kerja)->nama_client;

            

            $createData = [
                'karyawan_id'           => $request->id_karyawan,
                'nama_karyawan'         => $getDataKaryawan->nama_karyawan,
                'divisi'                => $divisi,
                'jabatan'               => $jabatan,
                'alasan'                => $request->alasan,
                'jam_keluar'            => $request->jam_keluar,
                'jam_masuk'             => $request->jam_masuk,
                'tanggal_pembuatan'     => $request->tanggal,
                'ttd_karyawan'          => $r,
                'kembali'               => $request->kembali,
                'status'                => $jabatan == 'Manager' ? 2 : 0,
                'id_client'             => $getDataKaryawan->lokasi_kerja,
            ];

            $result = [
                'pesan' => 'Form izin berhasil dibuat',
                'data'  => $createData,
                'ttd'   => $pesan,
            ];
        }else {
            $result = [
                'pesan'     => 'Izin Karyawan Eksternal sedang dalam pengembangan',
            ];
        }
        $saveDB = Izin::create($createData);
        if($saveDB) {
            return response()->json($result,200);
        }
        return response()->json(['pesan' => 'Server error'],500);

    }

    function get_izin_karyawan(Request $request) {

        if($request->id_karyawan == null) {
            return response()->json(['pesan' => 'ID karyawan dibutuhkan'],404);
        }

        $data   = Izin::where('karyawan_id',$request->id_karyawan)->orderBy('created_at','DESC')->get();
        $cek    = Izin::where('karyawan_id',$request->id_karyawan)->count();
        $result = [];

        foreach ($data as $key ) {
            $result[] = [
                'id'                => $key['id'],
                'id_karyawan'       => $key['karyawan_id'],
                'nama_karyawan'     => $key['nama_karyawan'],
                'divisi'            => $key['divisi'],
                'jabatan'           => $key['jabatan'],
                'tanggal'           => Carbon::parse($key['tanggal_pembuatan'])->translatedFormat('d F Y'),
                'jam_keluar'        => $key['jam_keluar'] == null ? '-' : $key['jam_keluar'],
                'jam_masuk'         => $key['jam_masuk'] == null ? '-' : $key['jam_masuk'],
                'alasan'            => $key['alasan'],
                'kembali'           => $key['kembali'] == 1 ? 'Tidak' : 'Ya',
                'info'              => $this->info_status($key['status']),
                'acc'               => $key['status'] == 4 ? 1 : 0,
                    'created_at'    => $key['created_at'],
                    'disetujui_oleh'    => $key['disetujui_oleh'],
                'detail'            => $this->detail_data($key['id'])
            ];
        }

        if($cek > 0) {
            return response()->json(['data' => $result],200);
        }
        return response()->json(['data' => $result],404);


    }

    function get_izin_manager(Request $request) {
        if($request->id_karyawan == null) {
            return response()->json(['pesan' => 'ID karyawan dibutuhkan'],404);
        }

        $id_divisi      = Karyawan::where('id_karyawan',$request->id_karyawan)->first()->divisi;
        $nama_divisi    = Divisi::find($id_divisi)->nama_divisi;

        $data           = Izin::where('divisi',$nama_divisi)->where('id_karyawan','!=',$request->id_karyawan)->where('status','>=',0)->orderBy('created_at','DESC')->get();
        $cek            = Izin::where('divisi',$nama_divisi)->where('status','>=',0)->count();

        $result         = [];

        foreach ($data as $key) {
            $result[]   = [
                'id'                => $key['id'],
                'foto_profile'      => foto_profile($key['karyawan_id']),

                'id_karyawan'       => $key['karyawan_id'],
                'nama_karyawan'     => $key['nama_karyawan'],
                'divisi'            => $key['divisi'],
                'jabatan'           => $key['jabatan'],
                'tanggal'           => Carbon::parse($key['tanggal_pembuatan'])->translatedFormat('d F Y'),
                'jam_keluar'        => $key['jam_keluar'] == null ? '-' : $key['jam_keluar'],
                'jam_masuk'         => $key['jam_masuk'] == null ? '-' : $key['jam_masuk'],
                'alasan'            => $key['alasan'],
                'kembali'           => $key['kembali'] == 1 ? 'Tidak' : 'Ya',
                'info'              => $this->info_status($key['status']),
                'acc'               => $key['status'] == 2 ? 1 : 0,
                    'created_at'    => $key['created_at'],
                    'disetujui_oleh'    => $key['disetujui_oleh'],
                'detail'            => $this->detail_data($key['id'])

            ];
        }

        if($cek > 0) {
            return response()->json(['data' => $result],200);
        }
        return response()->json(['data' => $result],404);

    }

    function get_izin_hrd(Request $request) {
        if($request->id_karyawan == null) {
            return response()->json(['pesan' => 'ID karyawan dibutuhkan'],404);
        }

        $id_divisi      = Karyawan::where('id_karyawan',$request->id_karyawan)->first()->divisi;
        $nama_divisi    = Divisi::find($id_divisi)->nama_divisi;

        $data           = Izin::where('status','>=',1)->where('id_client',1)->orderBy('created_at','DESC')->get();
        $cek            = Izin::where('status','>=',1)->where('id_client',1)->count();

        $result         = [];

        foreach ($data as $key) {
            $result[]   = [
                'id'                => $key['id'],
                'id_karyawan'       => $key['karyawan_id'],
                'nama_karyawan'     => $key['nama_karyawan'],
                'divisi'            => $key['divisi'],
                'jabatan'           => $key['jabatan'],
                'tanggal'           => Carbon::parse($key['tanggal_pembuatan'])->translatedFormat('d F Y'),
                'jam_keluar'        => $key['jam_keluar'] == null ? '-' : $key['jam_keluar'],
                'jam_masuk'         => $key['jam_masuk'] == null ? '-' : $key['jam_masuk'],
                'alasan'            => $key['alasan'],
                'kembali'           => $key['kembali'] == 1 ? 'Tidak' : 'Ya',
                'info'              => $this->info_status($key['status']),
                'acc'               => $key['status'] == 4 ? 1 : 0,
                'disetujui_oleh'    => $key['disetujui_oleh'],
                'created_at'    => $key['created_at'],
                'detail'            => $this->detail_data($key['id'])

            ];
        }

        if($cek > 0) {
            return response()->json(['data' => $result],200);
        }
        return response()->json(['data' => $result],404);

    }

    function get_izin_direktur_hrd(Request $request) {
        if($request->id_karyawan == null) {
            return response()->json(['pesan' => 'ID karyawan dibutuhkan'],404);
        }  

        
        $id_divisi      = Karyawan::where('id_karyawan',$request->id_karyawan)->first()->divisi;
        $nama_divisi    = Divisi::find($id_divisi)->nama_divisi;

        $data       = Izin::where('status','>=',2)->where('id_client',1)->where('id_client',1)->orderBy('created_at','DESC')->get();


        $result         = [];
        if($nama_divisi == 'MPO') {
            $data       = Izin::where('divisi',$nama_divisi)->where('status','>=',0)->get();
            foreach ($data as $key) {
                $result[]   = [
                    'id'                => $key['id'],
                    'id_karyawan'       => $key['karyawan_id'],
                    'nama_karyawan'     => $key['nama_karyawan'],
                    'divisi'            => $key['divisi'],
                    'jabatan'           => $key['jabatan'],
                    'tanggal'           => Carbon::parse($key['tanggal_pembuatan'])->translatedFormat('d F Y'),
                    'jam_keluar'        => $key['jam_keluar'] == null ? '-' : $key['jam_keluar'],
                    'jam_masuk'         => $key['jam_masuk'] == null ? '-' : $key['jam_masuk'],
                    'alasan'            => $key['alasan'],
                    'kembali'           => $key['kembali'] == 1 ? 'Tidak' : 'Ya',
                    'info'              => $this->info_status($key['status']),
                    'acc'               => $key['status'] == 4 ? 1 : 0,
                    'created_at'    => $key['created_at'],
                    'disetujui_oleh'    => $key['disetujui_oleh'],
                    'detail'            => $this->detail_data($key['id'])
    
                ];
            }
            return response()->json(['data' => $result],200);
        }
        foreach ($data as $key) {
            $result[]   = [
                'id'                => $key['id'],
                'id_karyawan'       => $key['karyawan_id'],
                'nama_karyawan'     => $key['nama_karyawan'],
                'divisi'            => $key['divisi'],
                'jabatan'           => $key['jabatan'],
                'tanggal'           => Carbon::parse($key['tanggal_pembuatan'])->translatedFormat('d F Y'),
                'jam_keluar'        => $key['jam_keluar'] == null ? '-' : $key['jam_keluar'],
                'jam_masuk'         => $key['jam_masuk'] == null ? '-' : $key['jam_masuk'],
                'alasan'            => $key['alasan'],
                'kembali'           => $key['kembali'] == 1 ? 'Tidak' : 'Ya',
                'info'              => $this->info_status($key['status']),
                'acc'               => $key['status'] == 4 ? 1 : 0,
                    'created_at'    => $key['created_at'],
                    'disetujui_oleh'    => $key['disetujui_oleh'],
                'detail'            => $this->detail_data($key['id'])

            ];
        }
        return response()->json(['data' => $result],200);

    }

    function update(Request $request) {
        if($request->id_karyawan == null) {
            return response()->json(['pesan' => 'ID karyawan dibutuhkan'],404);
        }

        $ceking = $this->ceking_id_karyawan($request->id_karyawan);

        if($ceking == FALSE ) {
            return response()->json(['pesan' => 'Akun tidak ditemukan'],401);
        }

        $id_izin    = $request->id_izin;
        $data       = Izin::find($id_izin);

        if($data){
            $cek_TTD = $this->cek_ttd($request->id_karyawan);   
            if($cek_TTD == FALSE) {
                return redirect()->route('create-tanda-tangan-m',['id_karyawan' => $request->id_karyawan]);
            }
            $id_divisi      = Karyawan::where('id_karyawan',$request->id_karyawan)->first()->divisi;
            $nama_divisi    = Divisi::find($id_divisi)->nama_divisi;
            
            $roles          = User::where('id_karyawan',$request->id_karyawan)->first()->roles;
            if($roles == 'direktur') {
                if($nama_divisi == 'MPO') {
                    $update                 = Izin::find($id_izin);
                    $update->ttd_mengetahui =  $cek_TTD['path'];
                    $update->status         = 1;
                    $update->update();
                    $pesan                  = ['pesan' => 'Izin ID Karyawan: '.$data->karyawan_id. ' Telah ditandatangani manager divisi'];
                }else {
                    if($data->status == 3) {
                        $update                 = Izin::find($id_izin);
                        $update->status         = 4;
                        $update->disetujui_pada = Carbon::now()->translatedFormat('d F Y');
                        $update->disetujui_oleh = "Direktur HRD";
                        $update->update();
                        $pesan                  = ['pesan' => 'Izin ID Karyawan: '.$data->karyawan_id. ' Telah disetujui Direktur HRD'];

                    }else {
                        $update                 = Izin::find($id_izin);
                        $update->ttd_direktur =  $cek_TTD['path'];
                        $update->status         = 3;
                        $update->update();
                        $pesan                  = ['pesan' => 'Izin ID Karyawan: '.$data->karyawan_id. ' Telah ditandatangani  Direktur HRD'];

                    }   
                }
            }else if($roles == 'hrd') {
                $update             = Izin::find($id_izin);
                $update->status     = 2;
                $update->ttd_hrd    = $cek_TTD['path'];
                $update->update();
                $pesan              = ['pesan' => 'Izin ID Karyawan: '.$data->karyawan_id. ' Telah ditandatangani Supervisor HRD'];

            }else if($roles == 'manajer') {
                $update         =   Izin::find($id_izin);
                $update->status = 2;
                $update->ttd_mengetahui = $cek_TTD['path'];
                $update->update();
                $pesan          = ['pesan' => 'Izin ID Karyawan : '.$data->karyawan_id.' Telah ditandatangani Manager'];
            }
            else {
                // $pesan = ['status' => TRUE];

                return response()->json(['pesan' => 'Gagal Update'],422);
            }
            return response()->json($pesan,200);
        }
        return response()->json(['data' => $data],404);
    }

    function ceking_id_karyawan($id) {
        $cek = User::where('id_karyawan',$id)->count();
        if($cek == 0) {
            return FALSE;
        }

        return TRUE;
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
        $data = Izin::find($id);

        $result = [
            'id_karyawan'           => $data->karyawan_id,
            'nama_karyawan'         => $data->nama_karyawan,
            'divisi'                => $data->divisi,
            'jabatan'               => $data->jabatan,
            'tanggal'               => Carbon::parse($data->tanggal_pembuatan)->translatedFormat('d F Y'),
            'jam_keluar'            => $data->jam_keluar == null ? '-' : $data->jam_keluar,
            'jam_masuk'             => $data->jam_masuk == null ? '-' : $data->jam_masuk,
            'alasan'                => $data->alasan,
            'kembali'               => $data->kembali == 1 ? 'Tidak' : 'Ya',
            'disetujui_oleh'        => $data->disetujui_oleh,
            'ttd_karyawan'          => $data->ttd_karyawan == null ? "" : asset($data->ttd_karyawan),

            'ttd_manager'           => $data->ttd_mengetahui == null ? "" : asset($data->ttd_mengetahui),
            'nama_manager'          => $data->ttd_mengetahui == null ? "" : $this->getNameByPath($data->ttd_mengetahui),
            
            'ttd_spv_hrd'           => $data->ttd_hrd == null ? "" : asset($data->ttd_hrd),
            'nama_spv_hrd'          => $data->ttd_hrd == null ? "" : $this->getNameByPath($data->ttd_hrd),

            'ttd_dir_hrd'           => $data->ttd_direktur == null ? "" : asset($data->ttd_direktur),
            'nama_dir_hrd'          => $data->ttd_direktur == null ? "" : $this->getNameByPath($data->ttd_direktur),

            'link'                  => $data->status == 4 ? $this->getLinkDownload($data->id) : '',

        ];
        return $result;
    }
    
    function getLinkDownload($id) {
        $url = route("download-izin",['id' => $id ]);
        return $url;
    }

    function viewFile($id) {
        $data = Izin::find($id);
        $jabatan        = Karyawan::where('id_karyawan',$data->karyawan_id)->with('jabatan')->first()->jabatan()->first()->nama_jabatan;
        $filename       = 'Detail Izin Keluar '.$data->nama_karyawan;
        $pdf            = PDF::loadview("layouts.pdf_view.pdfIzinPFI",['data' => $data,'jabatan' => $jabatan]);

        $pdf->setPaper('A4', 'landscape');
        return $pdf->stream($filename.'.pdf');

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

    function getNameByPath($path) {
        $get        = Filemanager::where('path',$path)->first()->id_karyawan;
        $getName    = Karyawan::where('id_karyawan',$get)->first()->nama_karyawan;

        return $getName;
    }

}

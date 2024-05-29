<?php

namespace App\Http\Controllers\Api;

use PDF;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use App\Models\Lembur;
use App\Models\Divisi;
use App\Models\Clients;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\Filemanager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class LemburController extends Controller
{
    function get_data_lembur(Request $request){
        $data       = Lembur::where('id_karyawan',$request->id_karyawan)->orderBy('created_at','DESC')->get();
        $divisi     = Karyawan::where('id_karyawan',$request->id_karyawan)->first()->divisi()->first()->nama_divisi;
        $result     = [];

        foreach($data as $key) {
            $result[] = [
                'id' => $key['id'],
                'nama_karyawan' => $key['nama_karyawan'],
                'id_karyawan'   => $key['id_karyawan'],
                'divisi'        => $key['divisi'],
                'jabatan'       => $key['jabatan'],
                'lokasi_kerja'  => $key['lokasi_kerja'],
                'alasan_lembur' => $key['alasan_lembur'],
                'jam_mulai'     => $key['jam_mulai'],
                'jam_selesai'   => $key['jam_selesai'],
                'tugas'         => $key['tugas'],
                'total_jam'     => $key['total_jam'],
                'tanggal_lembur'=> Carbon::parse($key['tanggal_lembur'])->translatedFormat('d F Y'),
                'info'          =>  $this->info_status($divisi,$key['status']),
                'acc'           => $key['status'] == 4 ? 1 : 0,
                'disetujui_oleh'    => $key['disetujui_oleh'],
                'detail'        => $this->detail_data($key['id']),
                'created_at'    => $key['created_at']
            ];
        }
        return response()->json([
                'status'   => 200,
                'data'      => $result
            ]);
    }

    function create_data_lembur(Request $request) {
        $cek_TTD                = $this->cek_ttd($request->id_karyawan);
        $tipeKaryawan           = $this->tipeKaryawan($request->id_karyawan);
        $cek_data_by_tanggal    = Lembur::where('id_karyawan',$request->id_karyawan)->where('tanggal_lembur',$request->tanggal)->count();

        if($cek_data_by_tanggal > 0) {
            return response()->json(['pesan' => 'Lembur pada tanggal '.$request->tanggal.' telah tersedia']);
        }


        if($request->ttd == 0 || $request->ttd == null) {
            return response()->json(['pesan' => 'Dokumen belum ditandatangani'],404);
        }

        if(in_array($tipeKaryawan['karyawanType'],['kr-pusat','kr-project','manajer'])) {
            $hitungLembur       = $this->HitungLembur($request);

            if($hitungLembur['status'] == FALSE) {
                return response()->json(['pesan' => $hitungLembur['keterangan']],$hitungLembur['response']);
            }

            // return response()->json(['data' => ]);

            $getDataKaryawan    = Karyawan::where('id_karyawan',$request->id_karyawan)->first();
            $divisi             = Divisi::find($getDataKaryawan->divisi)->nama_divisi;
            $jabatan            = Jabatan::find($getDataKaryawan->jabatan)->nama_jabatan;
            $namaClient         = Clients::find($getDataKaryawan->lokasi_kerja)->nama_client;
            $s                  = 'Create lembur karyawan internal';
            $dataInput = [
                'nama_karyawan' => $getDataKaryawan->nama_karyawan,
                'id_karyawan'   => $request->id_karyawan,
                'lokasi_kerja'  => $namaClient,
                'divisi'        => $divisi,
                'jabatan'       => $jabatan,
                'jam_mulai'     => $request->jam_mulai,
                'jam_selesai'   => $request->jam_selesai,
                'tugas'         => $request->tugas,
                'alasan_lembur' => $request->alasan_lembur,
                'total_jam'     => $hitungLembur['keterangan'],
                'id_client'     => $getDataKaryawan->lokasi_kerja,
                'status'        => $jabatan == 'Manager' ? 2 : 0,
                'tanggal_lembur'=> Carbon::parse($request->tanggal)->format('Y-m-d'),
                'ttd_karyawan'  => $cek_TTD['path']
            ];
            $result             = [
                'status'        => $s,
                'dataInput'     => $dataInput
            ];
        }else {
            $s          = 'Create Lembur karyawan external';
            $result     = [
                'status'        => $s,
                'dataKaryawan'  => ''
            ] ;
        }
        Lembur::create($dataInput);

        return response()->json($result,200);
    }

    function get_data_lembur_spv_internal(Request $request) {
        $id_karyawan= $request->id_karyawan;
        $id_client  = $request->id_client;
        $divisi     = $request->divisi;
        if($divisi == 'MPO') {
            $data       = Lembur::where('divisi',$divisi)->where('status','>=',0)->orderBy('created_at','DESC')->get();
            $result     = [];

            foreach($data as $key) {
                $result[] = [
                    'id' => $key['id'],

                    'nama_karyawan' => $key['nama_karyawan'],
                    'id_karyawan'   => $key['id_karyawan'],
                    'divisi'        => $key['divisi'],
                    'jabatan'       => $key['jabatan'],
                    'lokasi_kerja'  => $key['lokasi_kerja'],
                    'alasan_lembur' => $key['alasan_lembur'],
                    'jam_mulai'     => $key['jam_mulai'],
                    'jam_selesai'   => $key['jam_selesai'],
                    'tugas'         => $key['tugas'],
                    'total_jam'     => $key['total_jam'],
                    'tanggal_lembur'=> Carbon::parse($key['tanggal_lembur'])->translatedFormat('d F Y'),
                    'info'          =>  $this->info_status($key['status']),
                    'disetujui_oleh'    => $key['disetujui_oleh'],
                    'acc'           => $key['status'] == 1 ? 1 : 0,
                    'created_at'    => $key['created_at'],
                    'detail'        => $this->detail_data($divisi,$key['id']),

                ];
            }

            return response()->json(['data' => $result ],200);

        }

        return response()->json(['pesan' => 'Divisi anda langsung ke Manager Divisi'],200);
    }

    function get_data_lembur_manager(Request $request) {

        $id_client   = $request->id_client;
        $divisi      = $request->divisi;
        $id_karyawan = $request->id_karyawan;

        if($id_karyawan == "" || $id_karyawan == null) {
            return response()->json(['pesan' => 'ID Karyawan dibutuhkan'],401);
        }


        if($divisi == 'MPO') {
            $dataLembur = Lembur::where('divisi',$divisi)->where('status','>=',1)->count();
            $data       = Lembur::where('divisi',$divisi)->where('status','>=',1)->orderBy('created_at','DESC')->get();
        }else {
            $data       = Lembur::where('divisi',$divisi)->where('id_karyawan','!=',$id_karyawan)->where('status','>=',0)->orderBy('created_at','DESC')->get();
            $dataLembur = Lembur::where('divisi',$divisi)->where('status','>=',0)->count();
        }

        if($dataLembur > 0) {
            $result     = [];

            foreach($data as $key) {
                $result[] = [
                    'id'            => $key['id'],
                    'foto_profile'      => foto_profile($key['id_karyawan']),

                    'nama_karyawan' => $key['nama_karyawan'],
                    'id_karyawan'   => $key['id_karyawan'],
                    'divisi'        => $key['divisi'],
                    'jabatan'       => $key['jabatan'],
                    'lokasi_kerja'  => $key['lokasi_kerja'],
                    'alasan_lembur' => $key['alasan_lembur'],
                    'jam_mulai'     => $key['jam_mulai'],
                    'jam_selesai'   => $key['jam_selesai'],
                    'tugas'         => $key['tugas'],
                    'total_jam'     => $key['total_jam'],
                    'tanggal_lembur'=> Carbon::parse($key['tanggal_lembur'])->translatedFormat('d F Y'),
                    'info'          =>  $this->info_status($divisi,$key['status']),
                    'disetujui_oleh'=> $key['disetujui_oleh'] == null ? ""  :  $key['disetujui_oleh'] ,
                    'acc'           => $this->acc_manager($divisi,$key['status']),
                    'created_at'    => $key['created_at'],
                    'detail'        => $this->detail_data($key['id']),

                ];
            }
            return response()->json(['status' => 200,'data' => $result],200);
        }else {
            return response()->json(['data' => ""],200);
        }

    }

    function get_data_lembur_spv_hrd(Request $request) {
        $id_client  = $request->id_client;
        $data       = Lembur::whereIn('divisi',['MPO','Finance'])->where('status','>=',1)->orderBy('created_at','DESC')->get();
        $dataLembur = Lembur::where('id_client',1)->where('status','>=',1)->count();
        $result     = [];
        $divisi     = "";
        if($dataLembur > 0) {
            foreach($data as $key) {
                $result[] = [
                    'id' => $key['id'],

                    'nama_karyawan' => $key['nama_karyawan'],
                    'id_karyawan'   => $key['id_karyawan'],
                    'divisi'        => $key['divisi'],
                    'jabatan'       => $key['jabatan'],
                    'lokasi_kerja'  => $key['lokasi_kerja'],
                    'alasan_lembur' => $key['alasan_lembur'],
                    'jam_mulai'     => $key['jam_mulai'],
                    'jam_selesai'   => $key['jam_selesai'],
                    'tugas'         => $key['tugas'],
                    'total_jam'     => $key['total_jam'],
                    'tanggal_lembur'=> Carbon::parse($key['tanggal_lembur'])->translatedFormat('d F Y'),
                    'info'          =>  $this->info_status($divisi,$key['status']),
                    'disetujui_oleh'    => $key['disetujui_oleh'],
                    'created_at'    => $key['created_at'],
                    'detail'        => $this->detail_data($key['id']),

                ];
            }
            return response()->json(['status' => 200,'data' => $result]);
        }
        return response()->json(['data' => $result]);


    }

    function get_data_lembur_dir_hrd(Request $request) {

        if($request->id_karyawan == null || $request->id_karyawan == "") {
            return response()->json(['pesan' => 'ID karyawan dibutuhkan'],422);
        }

        $cekUser = User::where('id_karyawan',$request->id_karyawan)->count();

        if($cekUser == 0) {
            return response()->json(['pesan' => 'Anda tidak memiliki akses'],422);
        }

        $roles = User::where('id_karyawan',$request->id_karyawan)->first()->roles;

        // memuat variable kosong
        $result             = [];
        $nama_divisi        = Karyawan::where('id_karyawan',$request->id_karyawan)->first()->divisi()->first()->nama_divisi;

        // Validasi
        if($nama_divisi == 'MPO') {

            $data           = Lembur::where('divisi',$nama_divisi)->where('status','>=',1)->orderBy('created_at','DESC')->get();

            foreach($data as $key) {
                $result[] = [
                                'id'                => $key['id'],
                                'nama_karyawan'     => $key['nama_karyawan'],
                                'id_karyawan'       => $key['id_karyawan'],
                                'divisi'            => $key['divisi'],
                                'jabatan'           => $key['jabatan'],
                                'lokasi_kerja'      => $key['lokasi_kerja'],
                                'alasan_lembur'     => $key['alasan_lembur'],
                                'jam_mulai'         => $key['jam_mulai'],
                                'jam_selesai'       => $key['jam_selesai'],
                                'tugas'             => $key['tugas'],
                                'total_jam'         => $key['total_jam'],
                                'tanggal_lembur'    => Carbon::parse($key['tanggal_lembur'])->translatedFormat('d F Y'),
                                'info'              => $this->info_status($nama_divisi,$key['status']),
                                'disetujui_oleh'    => $key['disetujui_oleh'],
                                'detail'            => $this->detail_data($key['id']),

                            ];
            }
        }else {
            $data           = Lembur::where('id_client',1)->where('status','>=',2)->orderBy('created_at','DESC')->get();

            foreach($data as $key) {
                $result[] = [
                                'id'                => $key['id'],
                                'nama_karyawan'     => $key['nama_karyawan'],
                                'id_karyawan'       => $key['id_karyawan'],
                                'divisi'            => $key['divisi'],
                                'jabatan'           => $key['jabatan'],
                                'lokasi_kerja'      => $key['lokasi_kerja'],
                                'alasan_lembur'     => $key['alasan_lembur'],
                                'jam_mulai'         => $key['jam_mulai'],
                                'jam_selesai'       => $key['jam_selesai'],
                                'tugas'             => $key['tugas'],
                                'total_jam'         => $key['total_jam'],
                                'tanggal_lembur'    => Carbon::parse($key['tanggal_lembur'])->translatedFormat('d F Y'),
                                'info'              => $this->info_status($nama_divisi,$key['status']),
                                'disetujui_oleh'    => $key['disetujui_oleh'],
                                'detail'            => $this->detail_data($key['id']),

                            ];
            }
        }
        return response()->json(['data' => $result],200);



    }

    function update_data_lembur_internal(Request $request) {

        // Ambil ID Karyawan yang melakukan update
        $as_user     = User::where('id_karyawan',$request->id_karyawan)->first()->roles;


        // Pengecekan Roles
        if(!in_array($as_user, ['manajer','direktur','hrd'])) {
            return response()->json(['pesan' => 'Anda tidak memiliki akses'],422);
        }

        // Pengecekan sudah di tanda tangan atau belum
        if($request->ttd == null || $request->ttd != 1) {
            return response()->json(['pesan' => "Dokumen belum ditandatangani"],422);
        }


        //  Ambil data lembur berdasarkan ID
        $dataLembur  = Lembur::find($request->id_lembur);


        // Memberikan Notifikasi apabila lembur tidak tersedia
        if(!$dataLembur) {
            return response()->json(['pesan' => 'Data tidak tersedia'],422);
        }


        // Ambil divisi dari ID Karyawan yang melakukan update data
        $divisi     = Karyawan::where('id_karyawan',$request->id_karyawan)->first()->divisi()->first()->nama_divisi;


        // Pengecekan tandatangan dari ID Karyawan yang melakukan update data
        $cek_TTD    = $this->cek_ttd($request->id_karyawan);

        // Memvalidasi apakah tanda tangan tersedia atau tidak
        if($cek_TTD == FALSE) {
            return redirect()->route('create-tanda-tangan-m',['id_karyawan' => $request->id_karyawan]);
        }

        // Updated data berdasarkan Roles

        if($as_user == 'manajer'){
            $dataLembur->status = 1;
            $dataLembur->ttd_manager = $cek_TTD['path'];
            $dataLembur->update();
            $pesan = ['pesan' => $dataLembur->nama_karyawan.' berhasil di setujui'];
        }

        if($as_user == 'hrd') {
            $dataLembur->status             = $as_user != 'MPO' ? 2 : 3;
            $dataLembur->ttd_admin_korlap   = $cek_TTD['path'];
            $dataLembur->update();
            $pesan = ['pesan' => $dataLembur->nama_karyawan.' berhasil di setujui'];
        }

        if($as_user == 'direktur' || $divisi == 'Direktur' ) {
            $divisiUpdated = $dataLembur->divisi;
            if($divisiUpdated != 'MPO') {
                if($dataLembur->status == 3) {
                    $dataLembur->disetujui_oleh = 'Direktur HRD';
                    $dataLembur->status = 4;
                    $dataLembur->update();
                    $r = ['pesan' => $dataLembur->nama_karyawan.' berhasil disetujui'];
                }else {
                    $dataLembur->ttd_direktur = $cek_TTD['path'];
                    $dataLembur->status = 3;
                    $dataLembur->update();
                    $r = ['pesan' => $dataLembur->nama_karyawan.' berhasil ditandatangani'];
                }
                $pesan = $r;

            }else {
                if($dataLembur == 4) {
                    $dataLembur->disetujui_oleh = 'Direktur HRD';
                    $dataLembur->status = 5;
                    $dataLembur->update();
                    $r = ['pesan' => $dataLembur->nama_karyawan.' berhasil disetujui'];
                }else {
                    $dataLembur->ttd_direktur = $cek_TTD['path'];
                    $dataLembur->status = 4;
                    $dataLembur->update();
                    $r = ['pesan' => $dataLembur->nama_karyawan.' berhasil ditandatangani'];
                }
                $pesan = $r;
            }
        }

        return response()->json($pesan,200);


    }

    function info_status($divisi,$status) {
        if($divisi == 'MPO') {
            if($status == 0) {
                $s = 'Menunggu Disetujui Supervisor';
            }else if($status == 1) {
                $s = 'Menunggu Ditanda Tangani Manager Divisi';
            }else if($status == 2) {
                $s = 'Menunggu Ditanda Tangani HRD';
            }else if ($status == 3) {
                $s = 'Menunggu Ditanda Tangani Direktur HRD';
            }else if($status == 4) {
                $s = 'Menunggu Disetujui Direktur HRD';
            }else if($status == 5 ){
                $s = 'Sudah Ditanda Tangani ';
            }
            else {
                $s = 'Status tidak diketahui';
            }
        }else {
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
        $data = Lembur::find($id);

        $result = [
            'id_karyawan'       => $data->id_karyawan,
            'nama_karyawan'     => $data->nama_karyawan,
            'divisi'            => $data->divisi,
            'jabatan'           => $data->jabatan,
            'lokasi_kerja'      => $data->lokasi_kerja,
            'tanggal_lembur'    => Carbon::parse($data->tanggal_lembur)->translatedFormat('d F Y'),
            'alasan'            => $data->alasan_lembur,
            'tugas'             => $data->tugas,
            'jam_mulai'         => $data->jam_mulai,
            'jam_selesai'       => $data->jam_selesai,
            'total_jam'         => $data->total_jam,
            'disetujui_oleh'    => $data->disetujui_oleh == null ? "" : $data->disetujui_oleh ,
            'ttd_karyawan'      => $data->ttd_karyawan == null ? "" : asset($data->ttd_karyawan),
            'ttd_manager'       => $data->ttd_manager == null ? "" : asset($data->ttd_manager),
            'nama_manager'      => $data->ttd_manager == null ? "" : $this->getNameByPath($data->ttd_manager),
            'ttd_spv_hrd'       => $data->ttd_admin_korlap == null ? "" : asset($data->ttd_admin_korlap),
            'nama_spv_hrd'      => $data->ttd_admin_korlap == null ? "" : $this->getNameByPath($data->ttd_admin_korlap),
            'ttd_dir_hrd'       => $data->ttd_direktur == null ? "" : asset($data->ttd_direktur),
            'nama_dir_hrd'      => $data->ttd_direktur == null ? "" : $this->getNameByPath($data->ttd_direktur),
            'link'              => $this->getLinkDownload($data->divisi,$id,$data->status),
        ];
        return $result;
    }

    function getLinkDownload($divisi,$id,$status) {
        if($divisi == 'MPO') {
            if($status == 5) {
                $link = route("download-lembur",['id' => $id ]);
            }else {
                $link = "";
            }
        }else {
            if($status == 4) {
                $link = route("download-lembur",['id' => $id ]);
            }else {
                $link = "";
            }
        }

        return $link;
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

    public function convertSvgToPng(Request $request)
    {
        $getPath        = Filemanager::where('id_karyawan',$request->id_karyawan)->where('slug','signature')->get();
        return response()->json(['data' =>  $getPath],200);
        // Cek apakah file ada dan mime type-nya adalah SVG
        if (file_exists($svgPath) && mime_content_type($svgPath) === 'image/svg+xml') {
            // Baca konten SVG
            $svgContent = file_get_contents($svgPath);

            // Konversi SVG ke PNG dengan Intervention Image
            $png = Image::make($svgContent)->encode('png');

            // Simpan PNG
            $pngPath = str_replace('.svg', '.png', $svgPath);
            file_put_contents($pngPath, $png);
        }
    }

    public function getShift(Request $request) {
        $idClient   = $request->id_client;
        if($idClient == Null) {
            return response()->json(['pesan' => 'Id Client tidak ada' ],404);
        }

        if($idClient == 1) {
            $data = 'Non Shift';
        }else {
            $data  = Shift::where('id_client',$idClient)->get();

        }
        return response()->json(['data' => $data],200);
    }

    function getNameByPath($path) {
        $get        = Filemanager::where('path',$path)->first()->id_karyawan;
        $getName    = Karyawan::where('id_karyawan',$get)->first()->nama_karyawan;

        return $getName;
    }

    function viewFile($id) {
        $data       = Lembur::find($id);
        $filename   = 'Lembur '.$data->nama_karyawan;
        $jabatan    = Karyawan::where('id_karyawan',$data->id_karyawan)->with('jabatan')->first()->jabatan()->first()->nama_jabatan;
        $getIDAdmin = Filemanager::where('path',$data->ttd_admin_korlap)->first()->id_karyawan;
        $nama_admin = Karyawan::where("id_karyawan",$getIDAdmin)->first()->nama_karyawan;
        $pdf        = PDF::loadview("layouts.pdf_view.pdfLemburPFI",['data' => $data,'nama_admin' => $nama_admin]);

        $pdf->setPaper('A4', 'landscape');
        return $pdf->download($filename.'.pdf');

    }

    function acc_manager($divisi,$status) {
        if($divisi == 'MPO') {
            if($status >= 2) {
                $acc = 1;
            }else {
                $acc = 0;
            }
        }else {
            if($status >= 1) {
                $acc = 1;
            }else {
                $acc = 0;
            }
        }
        return $acc;
    }

    function HitungLembur($request) {

        $ConvertMulai     = $request->tanggal." ".$request->jam_mulai.":00";
        $ConvertSelesai   = $request->tanggal." ".$request->jam_selesai.":00";

        $start           = Carbon::parse($ConvertMulai);
        $end             = Carbon::parse($ConvertSelesai);


        $selisih        = $end->diffInMinutes($start);

        // Konversi selisih waktu ke dalam format jam dan menit
        $jam        = floor($selisih / 60);
        $menit      = $selisih % 60;

        // Maksimal lembur per hari adalah 7 jam (420 menit)

        $maksimalLemburPerHari = 420; // 7 * 60 (menit)

        if ($selisih > $maksimalLemburPerHari) {
            return  ['status' => FALSE,'keterangan' => 'Maksimal lembur adalah 7 jam','response' => 422];
        }

        return [
            'status'    => TRUE,
            'Jam mulai' => $start,
            'Jam Selesai' => $end,
            'keterangan'   => $jam . ' jam '. $menit . ' menit',
        ];

    }

    function getDataShift($id_client) {

        $data   = Shift::where('id_client',$id_client)->get();

        $result = [];

        foreach($data as $key) {
            $result[] = [
                'id' => $key->id,
                'nama_shift' => $key->type.' '.$key->ke,
                'waktu_mulai'   => $key->waktu_mulai,
                'waktu_selesai' => $key->waktu_selesai,

            ];
        }

        return response()->json(['data' => $result]);
    }

    function createLemburKrExternal(Request $request) {
        $id_karyawan = $request->id_karyawan;

        if($id_karyawan  == null || $id_karyawan  == "") {
            return response()->json(['pesan' => "ID Karyawan dibutuhkan."],422);
        }

        $cekID = User::where('id_karyawan',$id_karyawan);

        if($cekID->count() == 0){
            return response()->json(['pesan' => "ID Karyawan tidak terdaftar."],404);
        }

        $dataUser = $cekID->first();

        if($dataUser->roles != 'karyawan') {
            return response()->json(['pesan' => 'Anda tidak memiliki akses'],422);
        }

        if($request->ttd == 0 || $request->ttd == "" || $request->ttd == null) {
            return response()->json(['pesan' => 'Dokumen belum ditandatangani'],422);
        }

        $id_client      = $dataUser->id_client;
        $dataKaryawan   = Karyawan::where('id_karyawan',$id_karyawan)->first();

        if(in_array($id_client,[3,4])) {
            $insertLembur = [
                'id_karyawan'       => $dataUser->id_karyawan,
                'nama_karyawan'     => $dataUser->name,
                'divisi'            => $dataKaryawan->divisi()->first()->nama_divisi,
                'jabatan'           => $dataKaryawan->jabatan()->first()->nama_jabatan,
                'lokasi_kerja'      => $dataKaryawan->lokasi_kerja,
                'id_shift'          => $request->id_shift,
                'tanggal_lembur'    => Carbon::parse($request->tanggal)->format('Y-m-d'),
                'jam_mulai'         => $request->jam_mulai,
                'jam_selesai'       => $request->jam_selesai,
                'jam_mulai_rencana_lembur'      => $request->jam_mulai_rl,
                'jam_selesai_rencana_lembur'    => $request->jam_selesai_rl,
                'jam_mulai_lembur_aktual'       => $request->jam_mulai_la,
                'jam_selesai_lembur_aktual'     => $request->jam_selesai_la,
                'alasan_lembur'     => $request->alasan_lembur,
                'tugas'             => $request->tugas,
                'status'            => 0,
                'ttd_karyawan'      => $this->ttd_karyawan($id_karyawan),
                'id_client'         => $dataKaryawan->lokasi_kerja,

            ];
        }else {
            $insertLembur = [
                'id_karyawan'       => $dataUser->id_karyawan,
                'nama_karyawan'     => $dataUser->name,
                'divisi'            => $dataKaryawan->divisi()->first()->nama_divisi,
                'jabatan'           => $dataKaryawan->jabatan()->first()->nama_jabatan,
                'lokasi_kerja'      => $dataKaryawan->lokasi_kerja,
                'tanggal_lembur'    => Carbon::parse($request->tanggal)->format('Y-m-d'),
                'batch'             => $request->batch,
                'group'             => $request->group,
                'id_shift'          => $request->id_shift,
                'jam_mulai'         => $request->jam_mulai,
                'jam_selesai'       => $request->jam_selesai,
                'alasan_lembur'     => $request->alasan_lembur,
                'tugas'             => $request->tugas,
                'status'            => 0,
                'id_client'         => $dataKaryawan->lokasi_kerja,
                'ttd_karyawan'      => $this->ttd_karyawan($id_karyawan),
                'total_jam'         => $this->HitungLembur($request)['keterangan'],
            ];

            // return response()->json(['data' => $insertLembur]);
        }

        $create = Lembur::create($insertLembur);


        return response()->json(['pesan' => 'Lembur berhasil dibuat.']);
    }

    function ttd_karyawan($id_karyawan) {
        $get = Filemanager::where('id_karyawan',$id_karyawan)->where('slug','signature')->first();

        if(!$get) {
            return response()->json(['pesan' => 'Anda belum memiliki tanda tangan.'],404);
        }

        return $get->path;
    }

    function getDataLemburAdmin(Request $request) {
        $id_karyawan = $request->id_karyawan;

        if($id_karyawan  == null || $id_karyawan  == "") {
            return response()->json(['pesan' => "ID Karyawan dibutuhkan."],422);
        }

        $cekID = User::where('id_karyawan',$id_karyawan);

        if($cekID->count() == 0){
            return response()->json(['pesan' => "ID Karyawan tidak terdaftar."],404);
        }

        $dataUser = $cekID->first();

        if(!in_array($dataUser->roles,['admin','korlap'])) {
            return response()->json(['pesan' => 'Anda tidak memiliki akses.'],401);
        }
        $dataMaster     = Lembur::where('id_client',$dataUser->id_client);
        $data           = $dataMaster->where('id_karyawan','!=',$id_karyawan)->get();
        $id_client      = $dataUser->id_client;

        $result = [];

        foreach($data as $key) {
            if(in_array($id_client,[3,4])) {
                $shift = Shift::find($key->id_shift);
                if($key->status == 0) {
                    $s = "Perlu ditandatangani.";
                } else if($key->status == 1) {
                    $s = "Download File Karyawan.";
                } else if($key->status == 2 ) {
                    $s = "Upload File Karyawan.";
                } else if($key->status == 3) {
                    $s = "Menunggu persetujuan";
                }else if($key->status == 4) {
                    $s = "Telah di setujui";
                }
                else {
                    $s = "";
                }
                $result [] = [
                    'id' => $key->id,
                    'id_karyawan'       => $key->id_karyawan,
                    'nama_karyawan'     => $key->nama_karyawan,
                    'divisi'            => $key->divisi,
                    'jabatan'           => $key->jabatan,
                    'lokasi_kerja'      => $key->lokasi_kerja,
                    'id_shift'          => $shift->type.' '.$shift->ke,
                    'tanggal_lembur'    => Carbon::parse($key->tanggal_lembur)->translatedFormat('d F Y'),
                    'jam_mulai'         => $key->jam_mulai,
                    'jam_selesai'       => $key->jam_selesai,
                    'jam_mulai_rencana_lembur'      => $key->jam_mulai_rl,
                    'jam_selesai_rencana_lembur'    => $key->jam_selesai_rl,
                    'jam_mulai_lembur_aktual'       => $key->jam_mulai_la,
                    'jam_selesai_lembur_aktual'     => $key->jam_selesai_la,
                    'alasan_lembur'     => $key->alasan_lembur,
                    'tugas'             => $key->tugas,
                    'info'              => $s,
                    'acc'               => $key->ttd_admin_korlap == null ? 0 : 1,
                    'disetujui_oleh'    => $key->disetujui_oleh,
                    'detail'            =>  $this->detailLemburAdminKorlap($key->id)
                ];
            }else {
                $result[]   = [
                    'id'            => $key->id,
                    'foto_profile'  => foto_profile($key->id_karyawan),
                    'id_karyawan'   => $key->id_karyawan,
                    'nama_karyawan' => $key->nama_karyawan,
                    'divisi'        => $key->divisi,
                    'jabatan'       => $key->jabatan,
                    'alasan_lembur' => $key->alasan_lembur,
                    'tugas'         => $key->tugas,
                    'jam_mulai'     => $key->jam_mulai,
                    'jam_selesai'   => $key->jam_selesai,
                    'total_jam'     => $key->total_jam,
                    'tanggal_lembur'=> Carbon::parse($key->tanggal_lembur)->translatedFormat("d F Y"),
                    'info'          => $key->status == 0 ? "Perlu ditandatangani" : "Telah disetujui",
                    'acc'           => $key->ttd_admin_korlap == null ? 0 : 1,
                    'detail'        => $this->detailLemburAdminKorlap($key->id)

                ];
            }
        }

        return response()->json(['data' => $result]);
    }

    function detailLemburAdminKorlap($id_lembur) {
        $get = Lembur::find($id_lembur);
        $shift = Shift::find($get->id_shift);

        if(!in_array($get->id_client,[3,4])) {
            $result = [
                'id_karyawan'       => $get->id_karyawan,
                'nama_karyawan'     => $get->nama_karyawan,
                'divisi'            => $get->divisi,
                'jabatan'           => $get->jabatan,
                'alasan_lembur'     => $get->alasan_lembur,
                'tugas'             => $get->tugas,
                'jam_mulai'         => $get->jam_mulai,
                'jam_selesai'       => $get->jam_selesai,
                'total_jam'         => $get->total_jam,
                'tanggal_lembur'    => Carbon::parse($get->tanggal_lembur)->translatedFormat('d F Y'),
                'ttd_karyawan'      => $get->ttd_karyawan,
                'ttd_admin_korlap'  => $get->ttd_admin_korlap
            ];

        }else {

            $result = [
                'id_karyawan'       => $get->id_karyawan,
                'nama_karyawan'     => $get->nama_karyawan,
                'divisi'            => $get->divisi,
                'jabatan'           => $get->jabatan,
                'lokasi_kerja'      => $get->lokasi_kerja,
                'id_shift'          => $shift->type.' '.$shift->ke,
                'tanggal_lembur'    => Carbon::parse($get->tanggal_lembur)->translatedFormat('d F Y'),
                'jam_mulai'         => $get->jam_mulai,
                'jam_selesai'       => $get->jam_selesai,
                'jam_mulai_rencana_lembur'      => $get->jam_mulai_rl,
                'jam_selesai_rencana_lembur'    => $get->jam_selesai_rl,
                'jam_mulai_lembur_aktual'       => $get->jam_mulai_la,
                'jam_selesai_lembur_aktual'     => $get->jam_selesai_la,
                'alasan_lembur'     => $get->alasan_lembur,
                'tugas'             => $get->tugas,
                'ttd_karyawan'      => $get->ttd_karyawan,
                'ttd_admin_korlap'  => $get->ttd_admin_korlap,
                'link_download_file'=> route("lembur-download-perorang",['hash' => HashVariable($get->id)]),
                'link_uploaded'     => $get->id_filemanager == null ? "" : asset(Filemanager::find($get->id_filemanager)->path),
            ];
        }

        return $result;
    }

    function getLemburSpv($tipe_karyawan,Request $request) {

        $isSPV = isSupervisorProject($request->id_karyawan);

        return response()->json(['pesan']);
    }

}

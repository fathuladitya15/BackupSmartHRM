<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use App\Models\Lembur;
use App\Models\Karyawan;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Clients;
use App\Models\Filemanager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class LemburController extends Controller
{
    function get_data_lembur(Request $request){
        $data       = Lembur::where('id_karyawan',$request->id_karyawan)->get();
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

        if($request->id_karyawan == 001) {
            $jm = Carbon::createFromFormat('h:i A', $request->jam_mulai);
            $js = Carbon::createFromFormat('h:i A', $request->jam_selesai);

            $result = [
                'data'      => $request->all(),
                'Tanggal'   => Carbon::parse($request->tanggal)->format('Y-m-d'),
                'jam_mulai' => $jm->format('H:i'),
                'jam_selesai' => $js->format('H:i'),
            ];
            return response()->json($result);
        }

        if($request->ttd == 0) {
            return response()->json(['pesan' => 'Dokumen belum ditandatangani'],404);
        }


        if(in_array($tipeKaryawan['karyawanType'],['kr-pusat','kr-project'])) {
            $jm = Carbon::createFromFormat('h:i A', $request->jam_mulai);
            $js = Carbon::createFromFormat('h:i A', $request->jam_selesai);

            $getDataKaryawan    = Karyawan::where('id_karyawan',$request->id_karyawan)->first();
            $divisi             = Divisi::find($getDataKaryawan->divisi)->nama_divisi;
            $jabatan            = Jabatan::find($getDataKaryawan->jabatan)->nama_jabatan;
            $namaClient         = Clients::find($getDataKaryawan->lokasi_kerja)->nama_client;
            $s                  = 'Create lembur karyawan internal';
            $dataInput = [
                'nama_karyawan' => $getDataKaryawan->nama_karyawan,
                'id_karyawan'   => $request->id_karyawan,
                'lokasi_kerja'  => $getDataKaryawan->lokasi_kerja,
                'divisi'        => $divisi,
                'jabatan'       => $jabatan,
                'jam_mulai'     => $jm->format('H:i'),
                'jam_selesai'   => $js->format('H:i'),
                'tugas'         => $request->tugas,
                'alasan_lembur' => $request->alasan_lembur,
                'total_jam'     => $request->total_jam,
                'id_client'     => $namaClient,
                'status'        => 0,
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
            $data       = Lembur::where('divisi',$divisi)->where('status','>=',0)->get();
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
                    'detail'        => $this->detail_data($divisi,$key['id']),

                ];
            }

            return response()->json(['data' => $result ],200);

        }

        return response()->json(['pesan' => 'Divisi anda langsung ke Manager Divisi'],200);
    }

    function get_data_lembur_manager(Request $request) {
        $id_client  = $request->id_client;
        $divisi     = $request->divisi;
        $data       = Lembur::where('divisi',$divisi)->where('status','>=',0)->get();
        $dataLembur = Lembur::where('divisi',$divisi)->where('status','>=',0)->count();
        if($dataLembur > 0) {
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
                    'disetujui_oleh'    => $key['disetujui_oleh'],
                    'detail'        => $this->detail_data($key['id']),

                ];
            }
            return response()->json(['status' => 200,'data' => $result]);
        }
        return response()->json(['data' => $result]);

    }

    function get_data_lembur_spv_hrd(Request $request) {
        $id_client  = $request->id_client;
        $data       = Lembur::whereIn('divisi',['MPO','Finance'])->where('status','>=',1)->get();
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
                    'detail'        => $this->detail_data($key['id']),

                ];
            }
            return response()->json(['status' => 200,'data' => $result]);
        }
        return response()->json(['data' => $result]);


    }

    function get_data_lembur_dir_hrd(Request $request) {
        if($request->id_karyawan == null) {
            return response()->json(['pesan' => 'ID karyawan dibutuhkan'],404);
        }
        $data        = Lembur::where('id_client',1)->where('status','>=',2)->get();
        $dataLembur  = Lembur::where('id_client',1)->where('status','>=',2)->count();
        $result      = [];
        // $roles      = User::where('id_karyawan',$req)
        $divisi      = Karyawan::where('id_karyawan',$request->id_karyawan)->first()->divisi;
        $nama_divisi = Divisi::find($divisi)->nama_divisi;

        if($dataLembur > 0) {
            if($nama_divisi == 'MPO') {
                $data       = Lembur::where('divisi',$nama_divisi)->where('status','>=',0)->get();

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
                        'info'          =>  $this->info_status($nama_divisi,$key['status']),
                        'disetujui_oleh'    => $key['disetujui_oleh'],
                        'detail'        => $this->detail_data($key['id']),

                    ];
                }
            }
            $nama_divisi =  "";
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
                    'info'          =>  $this->info_status($nama_divisi,$key['status']),
                    'disetujui_oleh'    => $key['disetujui_oleh'],
                    'detail'        => $this->detail_data($key['id']),

                ];
            }
            return response()->json(['status' => 200,'data' => $result]);
        }
        return response()->json(['data' => $result]);

    }

    function update_data_lembur_internal(Request $request) {
        $dataLembur  = Lembur::find($request->id_lembur);
        $UpdatedBy   = Karyawan::where('id_karyawan',$request->id_karyawan)->first();
        $Divisi      = Divisi::find($UpdatedBy->divisi)->nama_divisi;
        $as_user     = User::where('id_karyawan',$request->id_karyawan)->first()->roles;

        $cek_TTD    = $this->cek_ttd($request->id_karyawan);
        if($cek_TTD == FALSE) {
            return redirect()->route('create-tanda-tangan-m',['id_karyawan' => $request->id_karyawan]);
        }

        if($dataLembur) {
            if($as_user == 'manajer') {
                $res = [
                    'status' => 200,
                    'pesan' => 'Update by manager'
                ];
                $dataLembur->ttd_manager = $cek_TTD['path'];
                $dataLembur->status = 1;
                $dataLembur->update();
            }else if($as_user == 'direktur') {
                if($Divisi == 'MPO') {
                    $res = [
                        'status' => 200,
                        'pesan' => 'Lembur ID Karyawan: '.$dataLembur->id_karyawan. ' Telah ditandatangani manager divisi'
                    ];
                    $dataLembur->ttd_manager = $cek_TTD['path'];
                    $dataLembur->status = 1;
                    $dataLembur->update();
                }else {
                    if($dataLembur->status == 3) {
                        $res = [
                            'status' => 200,
                            'pesan' => 'Lembur ID Karyawan: '.$dataLembur->id_karyawan. ' Telah disetujui direktur HRD',

                        ];
                        $dataLembur->disetujui_oleh = 'Direktur HRD';
                        $dataLembur->status = 4;
                        $dataLembur->update();
                    }else {
                        $res = [
                            'status' => 200,
                            'pesan' => 'Lembur ID Karyawan: '.$dataLembur->id_karyawan. ' Telah tandatangani direktur HRD',

                        ];
                        $dataLembur->ttd_direktur = $cek_TTD['path'];
                        $dataLembur->status = 3;
                        $dataLembur->update();

                    }
                 }
            }else if($as_user == 'hrd') {
                $res = [
                    'status' => 200,
                    'pesan' => 'Lembur ID Karyawan: '.$dataLembur->id_karyawan. ' Telah tandatangani Supervisor HRD',

                ];
                $dataLembur->ttd_admin_korlap = $cek_TTD['path'];
                $dataLembur->status = 2;
                $dataLembur->update();
            }
            return response()->json($res);
        }
        return response()->json(['status' => 404,'pesan' => 'Data tidak tersedia','data' => $Divisi]);

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
            'disetujui_oleh'    => $data->disetujui_oleh,
            'ttd_karyawan'      => $data->ttd_karyawan == null ? "" : asset($data->ttd_karyawan),
            'ttd_manager'       => $data->ttd_manager == null ? "" : asset($data->ttd_manager),
            'ttd_spv_hrd'       => $data->ttd_admin_korlap == null ? "" : asset($data->ttd_admin_korlap),
            'ttd_dir_hrd'       => $data->ttd_direktur == null ? "" : asset($data->ttd_direktur),
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

    public function convertSvgToPng($svgPath)
    {
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

    public function testingSaveDate(Request $request) {

        $hitungLembur = $this->HitungLembur($request->jam_mulai,$request->jam_selesai);
        if($hitungLembur['status'] == FALSE) {
            return response()->json(['pesan' => $hitungLembur['keterangan']]);
        }

        return response()->json([
            'data' => $request->all(),
            'jam mulai' => $request->jam_mulai,
            'jam selesai' => $request->jam_selesai,
            'ket' => $hitungLembur['keterangan']]);
    }

    function HitungLembur($jam_mulai,$jam_selesai) {
        $mulai          = Carbon::createFromFormat('H:i', $jam_mulai);
        $selesai        = Carbon::createFromFormat('H:i', $jam_selesai);

        $selisihMenit   = $selesai->diffInMinutes($mulai);

        // Mengonversi selisih menit menjadi jam dan menit
        $jam        = floor($selisihMenit / 60);
        $menit      = $selisihMenit % 60;


        if($menit == 0) {
            $res = $jam." jam";
        }else if($jam == 0) {
            $res = $menit." menit";
        }else {
            $res = "$jam jam $menit menit";
        }

        if($jam > 7 || $menit > 0) {
            return  ["status" => FALSE,'keterangan' => "Total lembur tidak boleh melebihi 7 jam."];

        }

        return ['status' => TRUE,'keterangan' =>  $res];

    }

}

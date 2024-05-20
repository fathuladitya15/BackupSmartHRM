<?php

namespace App\Http\Controllers\Api;

use Str;
use DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Role;
use App\Models\Clients;
use App\Models\Jabatan;
use App\Models\Filemanager;
use App\Models\Divisi;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;



class KaryawanController extends Controller
{
    function create(Request $request) {
        if($request->id_karyawan_pembuat == "" || $request->id_karyawan_pembuat == null) {
            return response()->json(['pesan' => 'Anda tidak memiliki akses'],401);
        }

        $id_karyawan_created = $request->id_karyawan_pembuat;

        // Cek Roles ID Karyawan yang buat

        $cek_id_karyawan_created = User::where('id_karyawan',$id_karyawan_created)->count();

        if($cek_id_karyawan_created == 0) {
            return response()->json(['pesan' => 'ID Karyawan pembuat tidak ditemukan'],404);
        }

        $roles_create = User::where("id_karyawan",$id_karyawan_created)->first()->roles;

        if($roles_create != 'hrd') {
            return response()->json(['pesan' => 'Anda tidak memiliki akses.'],401);
        }


        // $validasiData = $this->validated_data($request);
        $rules = [
            'status_akun'        => 'required',
            'nama_lengkap'       => 'required',
            'no_hp'              => 'required',
            'tempat_lahir'       => 'required',
            'tanggal_lahir'      => 'required',
            'jenis_kelamin'      => 'required',
            'pendidikan'         => 'required',
            'email'              => 'required|email|unique:users,email',
            'password'           => [
                        'string',
                        'min:8',             // must be at least 8 characters in length
                        'regex:/[a-z]/',      // must contain at least one lowercase letter
                        'regex:/[A-Z]/',      // must contain at least one uppercase letter
                        'regex:/[0-9]/',      // must contain at least one digit
                        'regex:/[@$!%*#?&.-]/', // must contain a special character
            ],
            'alamat_ktp'        => 'required',
            'alamat_domisili'   => 'required',
            'usia'              => 'required',
            'agama'             => 'required',
            'tipe_akun'         => 'required',
            'id_karyawan'       => 'required|unique:users,id_karyawan',
            'kategori'          => 'required',
            'lokasi_kerja'      => 'required',
            'jabatan'           => 'required',
            'divisi'            => 'required',
            'join_date'         => 'required',
            'end_date'          => 'required',
            'gol_karyawan'      => 'required',
            'marital'           => 'required',
            'nomor_ktp'         => 'required|digits:16|unique:table_karyawan,nik',

            'pemilik_rekening'  => 'required|same:nama_lengkap',
            'no_rekening'       => 'required',
            'nama_bank'         => 'required',
            'kode_bank'         => 'required',
            'lokasi_cabang'     => 'required',
            'doc_pendukung'     => 'nullable|mimes:pdf',

        ];

        $message = [
            'status_akun.required'          => 'Status akun harus diisi.',
            'nama_lengkap.required'         => 'Nama Lengkap harus diisi.',
            'no_hp.required'                => 'Nomor Handphone harus diisi.',
            'tempat_lahir.required'         => 'Tempat lahir harus diisi.',
            'tanggal_lahir.required'        => 'Tanggal lahir harus diisi.',
            'jenis_kelamin.required'        => 'Jenis Kelamin harus diisi.',
            'pendidikan.required'           => 'Pendidikan harus diisi.',
            'email.required'                => 'Email harus diisi',
            'email.unique'                  => 'Email sudah terdaftar',
            'password.required'             => 'Bidang kata sandi wajib diisi.',
            'password.string'               => 'Bidang kata sandi harus berupa string.',
            'password.min'                  => 'Password minimal 8 karakter',
            'password.regex'                => 'Password harus mengandung setidaknya satu huruf kapital, satu huruf non-kapital, satu angka, dan satu karakter khusus.',
            'alamat_ktp.required'           => 'Alamat KTP harus diisi',
            'alamat_domisili.required'      => 'Alamat domisili harus diisi.',
            'tipe_akun.required'            => 'Tipe akun harus diisi',

            'id_karyawan.required'          => 'ID Karyawan harus diisi.',
            'id_karyawan.unique'            => 'ID Karyawan sudah terdaftar',
            'kategori.required'             => 'Kategori harus diisi.',
            'lokasi_kerja.required'         => 'Lokasi kerja harus diisi',
            'jabatan.required'              => 'Jabatan harus diisi',
            'divisi.required'               => 'Divisi harus diisi',
            'join_date.required'            => 'Tanggal bergabung harus diisi',
            'end_date.required'             => 'Tanggal berakhir harus diisi',
            'gol_karyawan.required'         => 'Golongan karyawan harus diisi',
            'marital.required'              => 'Status marital harus diisi',
            'nomor_ktp.required'            => 'Nomor KTP harus diisi',
            'nomor_ktp.digits'              => 'Nomor KTP harus 16 angka',
            'nomor_ktp.unique'              => 'Nomor KTP sudah terdaftar',
            'pemilik_rekening.required'     => 'Nama pemilik rekening harus diisi.',
            'pemilik_rekening.same'         => 'Nama pemilik rekening harus sama dengan nama lengkap.',
            'no_rekening.required'          => 'Nomor Rekening harus diisi',
            'nama_bank.required'            => 'Nama bank harus diisi.',
            'kode_bank.required'            => 'Kode bank harus diisi',
            'lokasi_cabang.required'        => 'Lokasi cabang bank harus diisi.',
            'cv.mimes'                      => "File harus dalam bentuk JPG, PNG, JPEG, PDF",
            'doc_pendukung.mimes'           => "File harus dalam bentuk PDF",

        ];


        $validator = Validator::make($request->all(),$rules,$message);

        if($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->messages() as $key => $messages) {
                $errors[$key] = $messages[0]; // Ambil pesan kesalahan pertama untuk setiap field
            }
            return response()->json(['pesan' => $errors],422);
        }

        if($request->foto_profile) {
            $filename           = 'PP_' . time().$request->id_karyawan;
            $profileImagePath   = $this->saveImage($request, 'foto_profile','/filemanager/photo_profile',$filename);

            $dataInsert = [
                "filename"      => $filename.'.'.$profileImagePath['extension'],
                "path"          => $profileImagePath['path'],
                "extension"     => $profileImagePath['extension'],
                "id_karyawan"   => $request->id_karyawan,
                "slug"          => 'foto_profile' ,
                "keterangan"    => 'Foto Profile  Karyawan : '.$request->nama_lengkap,
                "status"        => 'Create New',
            ];
            Filemanager::create($dataInsert);

        }

        if($request->foto_ktp) {
            $filename       =   "KTP_".date("YmdHi").$request->id_karyawan;
            $ktpImagePath   = $this->saveImage($request,'foto_ktp','/filemanager/ktp',$filename);

            $dataInsert = [
                "filename"      => $filename.'.'. $ktpImagePath['extension'],
                "path"          => $ktpImagePath['path'],
                "extension"     => $ktpImagePath['extension'],
                "id_karyawan"   => $request->id_karyawan,
                "slug"          => 'ktp' ,
                "keterangan"    => 'Foto KTP  Karyawan : '.$request->nama_lengkap,
                "status"        => 'Create New',
            ];

            Filemanager::create($dataInsert);
        }

        if($request->foto_jkn) {
            $filename       =   "JKN_".date("YmdHi").$request->id_karyawan;
            $ktpImagePath   = $this->saveImage($request,'foto_jkn','/filemanager/jkn',$filename);

            $dataInsert = [
                "filename"      => $filename.'.'. $ktpImagePath['extension'],
                "path"          => $ktpImagePath['path'],
                "extension"     => $ktpImagePath['extension'],
                "id_karyawan"   => $request->id_karyawan,
                "slug"          => 'jkn' ,
                "keterangan"    => 'Foto JKN  Karyawan : '.$request->nama_lengkap,
                "status"        => 'Create New',
            ];

            Filemanager::create($dataInsert);
        }

        if($request->foto_kpj) {
            $filename       =    "KPJ_".date("YmdHi").$request->id_karyawan;
            $ktpImagePath   = $this->saveImage($request,'foto_kpj','/filemanager/kpj',$filename);

            $dataInsert = [
                "filename"      => $filename.'.'. $ktpImagePath['extension'],
                "path"          => $ktpImagePath['path'],
                "extension"     => $ktpImagePath['extension'],
                "id_karyawan"   => $request->id_karyawan,
                "slug"          => 'kpj' ,
                "keterangan"    => 'Foto KPJ  Karyawan : '.$request->nama_lengkap,
                "status"        => 'Create New',
            ];

            Filemanager::create($dataInsert);
        }

        if($request->foto_npwp) {
            $filename       =    "NPWP_".date("YmdHi").$request->id_karyawan;
            $ktpImagePath   = $this->saveImage($request,'foto_npwp','/filemanager/npwp',$filename);

            $dataInsert = [
                "filename"      => $filename.'.'. $ktpImagePath['extension'],
                "path"          => $ktpImagePath['path'],
                "extension"     => $ktpImagePath['extension'],
                "id_karyawan"   => $request->id_karyawan,
                "slug"          => 'npwp' ,
                "keterangan"    => 'Foto KPJ  Karyawan : '.$request->nama_lengkap,
                "status"        => 'Create New',
            ];

            Filemanager::create($dataInsert);
        }

        if($request->cv) {
            $filename       =    "CV_".date("YmdHi").$request->id_karyawan;
            $ktpImagePath   = $this->saveImage($request,'cv','/filemanager/cv',$filename);

            $dataInsert = [
                "filename"      => $filename.'.'. $ktpImagePath['extension'],
                "path"          => $ktpImagePath['path'],
                "extension"     => $ktpImagePath['extension'],
                "id_karyawan"   => $request->id_karyawan,
                "slug"          => 'cv' ,
                "keterangan"    => 'CV  Karyawan : '.$request->nama_lengkap,
                "status"        => 'Create New',
            ];

            Filemanager::create($dataInsert);
        }

        if($request->doc_pendukung) {
            $filename       =    "DocPendukung_".date("YmdHi").$request->id_karyawan;
            $ktpImagePath   = $this->saveImage($request,'doc_pendukung','/filemanager/lainnya',$filename);

            $dataInsert = [
                "filename"      => $filename.'.'. $ktpImagePath['extension'],
                "path"          => $ktpImagePath['path'],
                "extension"     => $ktpImagePath['extension'],
                "id_karyawan"   => $request->id_karyawan,
                "slug"          => 'lainnya' ,
                "keterangan"    => 'Dokumen Pendukung   Karyawan : '.$request->nama_lengkap,
                "status"        => 'Create New',
            ];

            Filemanager::create($dataInsert);
        }

        $userName = str_replace('-', '_', $request->input('id_karyawan'));

        //  Mulai transaksi
         DB::beginTransaction();

         try {
             // Simpan data ke tabel users
             $user = User::create([
                'name'          => $request->nama_lengkap,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'username'      => $userName,
                'roles'         => $request->tipe_akun,
                'id_karyawan'   => $request->id_karyawan,
                'id_client'     => $request->lokasi_kerja,
            ]);

             // Simpan data ke tabel karyawan
            Karyawan::create([
                'nama_karyawan'         => $request->nama_lengkap,
                'id_karyawan'           => $request->id_karyawan,
                'no_hp'                 => $request->no_hp,
                'tanggal_lahir'         => $request->tanggal_lahir,
                'tempat_lahir'          => $request->tempat_lahir,
                'jenis_kelamin'         => $request->jenis_kelamin,
                'nik'                   => $request->nomor_ktp,
                'no_kk'                 => $request->nomor_kk,
                'alamat'                => $request->alamat_ktp,
                'alamat_domisili'       => $request->alamat_domisili,
                'lokasi_kerja'          => $request->lokasi_kerja,
                'divisi'                => $request->divisi,
                'jabatan'               => $request->jabatan,
                'join_date'             => $request->join_date,
                'end_date'              => $request->end_date,
                'no_sio'                => $request->no_sio,
                'gol_karyawan'          => $request->gol_karyawan,
                'marital'               => $request->marital,
                'tJabatan'              => $request->tj_jabatan,
                'tTransport'            => $request->tj_transportasi,
                'nama_bank'             => $request->nama_bank,
                'cabang_bank'           => $request->lokasi_cabang,
                'kd_bank'               => $request->kode_bank,
                'norek_bank'            => $request->no_rekening,
                'status'                => 0,
                'status_karyawan'       => $request->status_akun,
                'usia'                  => $request->usia,
                'nama_s_i'              => $request->nama_suami_istri,
                'jmlh_anak'             => $request->jumlah_anak,
                'nama_ibu'              => $request->nama_ibu,
                'nama_anak'             => $request->nama_anak,
                'nama_bapa'             => $request->nama_bapak,
                'pendidikan'            => $request->pendidikan,
                'agama'                 => $request->agama,
                'kategori'              => $request->kategori,
                'no_npwp'               => $request->nomor_npwp,
                'no_kpj'                => $request->nomor_kpj,
                'no_jkn'                => $request->nomor_jkn,
            ]);

             // Commit transaksi jika semua operasi berhasil
             DB::commit();

             return response()->json(['pesan' => 'Data berhasil disimpan'], 200);

         } catch (\Exception $e) {
             // Rollback transaksi jika terjadi kesalahan
             DB::rollBack();
             return response()->json(['pesan' => 'Terjadi kesalahan saat menyimpan data', 'error' => $e->getMessage()], 500);
         }
    }

    function get_tipe_akun() {
        $tipe_akun  = Role::where('slug_role','!=','superadmin')->where('slug_role','!=' ,'karyawan')->orderBy('name_role','ASC')->get();

        $result = [];
        foreach ($tipe_akun as $key ) {
            $result[] = [
                'name' => $key->name_role,
                'value' => $key->slug_role
            ];
        }

        return response()->json(['data' => $result]);
    }

    function kategori_karyawan() {
        $result = [ [
            'name' => 'Karyawan Pusat (Kantor PT.PFI)',
            'value' => 'pusat',
        ],[
            'name' => 'Karyawan Internal Pusat (Project)',
            'value' => 'project',
        ]
        ];
        return response()->json(['data' => $result]);

    }

    function clients() {
        $data = Clients::orderBy('nama_client','ASC')->get();
        $result = [];
        foreach ($data as $key ) {
            $result[] = [
                'name' => $key->nama_client,
                'value' => $key->id,
            ];
        }

        return response()->json(['data' => $result]);

    }

    function jabatan() {
        $data = Jabatan::where('id_client',1)->orderBy('nama_jabatan','ASC')->get();

        $result = [];

        foreach ($data as $key ) {
            $result[] = [
                'name' => $key->nama_jabatan,
                'value' => $key->id,
            ];
        }

        return response()->json(['data' => $result]);
    }

    function divisi () {
        $data = Divisi::where('id_client',1)->orderBy('nama_divisi','ASC')->get();

        $result = [];

        foreach($data as $key) {
            $result[] = [
                'name' => $key->nama_divisi,
                'value' => $key->id
            ];
        }

        return response()->json(['data' => $result]);
    }

    function marital() {
        $result = [
            ['name' => 'TK/0'],
            ['name' => 'TK/1'],
            ['name' => 'TK/2'],
            ['name' => 'TK/3'],
            ['name' => 'K0'],
            ['name' => 'K1'],
            ['name' => 'K2'],
            ['name' => 'K3'],
            ['name' => 'K/I/0'],
            ['name' => 'K/I/1'],
            ['name' => 'K/I/2'],
            ['name' => 'K/I/3'],
        ];

        return response()->json(['data' => $result]);
    }

    function saveImage(Request $request, string $fieldname, string $customPath, string $customFilename) {
        $file  =  $request->file($fieldname);
        $input =  $request->input($fieldname);

        if ($file instanceof UploadedFile) {
            $extension  = $file->getClientOriginalExtension();
            $filename   = $customFilename . '.' . $extension;
            $path       = $customPath.'/'.$filename;
            $file->move(public_path($customPath.'/'),$filename);
            return [ 'path' =>$path ,'extension'=>$extension];
        }

        // Check if input is a base64 string
        if ($this->isBase64($input)) {
            $decodedData    = base64_decode($input);
            $extension      = $this->getBase64Extension($input);
            if (!in_array($extension, ['jpeg', 'jpg', 'png'])) {
                return response()->json(['pesan' => 'Format base64 tidak valid. Hanya JPEG, JPG, dan PNG yang diperbolehkan.']);
            }
            $filename       = $customFilename . '.' . $extension;
            $path           = $customPath. '/' . $filename;
            $savePath       = public_path($customPath. '/' . $filename);
            file_put_contents($savePath, $decodedData);
            return ['path'=>$path,'extension' => $extension];
        }
    }

    function isBase64($string) {
        $decoded_data = base64_decode($string, true);
        return $decoded_data !== false && base64_encode($decoded_data) === $string;
    }


    function getBase64Extension($base64String) {
        $finfo = finfo_open();
        $mimeType = finfo_buffer($finfo, base64_decode($base64String), FILEINFO_MIME_TYPE);
        finfo_close($finfo);
        $mimeToExt = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            // Add more mime types and their corresponding extensions here
        ];
        return $mimeToExt[$mimeType] ?? null;
    }


    function calculate(Request $request) {

        $id_karyawan = $request->id_karyawan;
        if($id_karyawan == "" || $id_karyawan == null ) {
            return response()->json(['pesan' => 'ID karyawan dibutuhkan'],401);
        }

        $dataLogin = User::where('id_karyawan',$id_karyawan)->first();

        if(!in_array($dataLogin->roles,['admin','korlap'])) {
            return response()->json(['pesan' => 'Anda tidak memiliki akses'],401);
        }

        $izin       = DB::table("table_izin as ti")
                            ->join('table_karyawan as us','us.id_karyawan','=','ti.karyawan_id')
                            ->where('ti.status','0')
                            ->where('ti.id_client','LIKE','%'.$dataLogin->id_client.'%')
                            ->count();
        $lembur      = DB::table("table_lembur as ti")
                            ->join('table_karyawan as us','us.id_karyawan','=','ti.id_karyawan')
                            ->where('ti.status','0')
                            ->where('ti.id_client','LIKE','%'.$dataLogin->id_client.'%')
                            ->count();
        $totalkr    = User::where('id_client',$dataLogin->id_client)->where('roles','karyawan')->count();
        $total      = $izin + $lembur;

        return response()->json(['totalApproval' => $total,'totalKr' => $total]);

        // $data = User::where('id_cli')
    }

    function get_dataKaryawan(Request $request) {
        $id_karyawan = $request->id_karyawan;

        if($id_karyawan == "" || $id_karyawan == null ) {
            return response()->json(['pesan' => 'ID Karyawan dibutuhkan.'],401);
        }

        $dataLogin = User::where("id_karyawan",$id_karyawan)->first();

        if($dataLogin->roles != 'hrd') {
            return response()->json(['pesan' => 'Anda tidak memiliki akses.'],401);
        }

        if($dataLogin->roles == 'hrd'){
            $data =  DB::table('table_karyawan as tk')->select('tk.*','us.name','us.roles')
                ->join('users as us','us.id_karyawan','=','tk.id_karyawan')
                ->where('us.roles','!=','karyawan')
                ->where('us.id','!=',$dataLogin->id)
                ->orderBy('created_at','DESC')
                ->get();
        }else {
            return response()->json(['pesan' => 'Anda tidak memiliki akses'],401);
        }

        $result = [];

        foreach($data as $key) {
            $result[] = [
                'nama_lengkap'      => Str::title($key->nama_karyawan),
                'id_karyawan'       => $key->id_karyawan,
                'foto_profile'      => foto_profile($key->id_karyawan),
                'divisi'            => Divisi::find($key->divisi)->nama_divisi,
                'jabatan'           => Jabatan::find($key->jabatan)->nama_jabatan,
                'join_date'         => Carbon::parse($key->join_date)->translatedFormat('d F Y'),
                'end_date'          => Carbon::parse($key->end_date)->translatedFormat('d F Y'),
                'gol_karyawan'      => $key->gol_karyawan,
                'marital'           => $key->marital,
                'lokasi_kerja'      => Clients::find($key->lokasi_kerja)->nama_client,
                'kategori'          => $key->kategori == 'pusat' ? 'Karyawan Pusat (Kantor PT.PFI)' : 'Karyawan Internal Pusat (Project)',
                'detail'            => $this->detail_data($key->id_karyawan),
                'dokumen'           => $this->detail_dokumen($key->id_karyawan),

            ];
        }

        return response()->json(['data' => $result]);

    }

    function detail_data($id_karyawan) {
        $dataUser = User::where('id_karyawan',$id_karyawan)->first();

        $dataKaryawan = Karyawan::where('id_karyawan',$id_karyawan)->first();

        $result = [
            'foto_profile'          => foto_profile($id_karyawan),
            'username'              => $dataUser->username,
            'tipe_akun'             => $dataUser->roles,
            'email'                 => $dataUser->email,
            'nama_lengkap'          => $dataUser->name,
            'nomor_hp'              => $dataKaryawan->no_hp,
            'tempat_lahir'          => $dataKaryawan->tempat_lahir,
            'tanggal_lahir'         => Carbon::parse($dataKaryawan->tanggal_lahir)->translatedFormat('d F Y'),
            'jenis_kelamin'         => $dataKaryawan->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan',
            'alamat'                => $dataKaryawan->alamat,
            'alamat_domisili'       => $dataKaryawan->alamat_domisili,
            'usia'                  => $dataKaryawan->usia,
            'agama'                 => $dataKaryawan->agama,
            'nama_suami_istri'      => $dataKaryawan->nama_s_i,
            'nama_anak'             => $dataKaryawan->nama_anak,
            'jumlah_anak'           => $dataKaryawan->jmlh_anak,
            'nama_ibu'              => $dataKaryawan->nama_ibu,
            'nama_ayah'             => $dataKaryawan->nama_bapa,
            'pendidikan'            => $dataKaryawan->pendidikan,

            'id_karyawan'           => $dataKaryawan->id_karyawan,
            'lokasi_kerja'          => Clients::find($dataUser->id_client)->nama_client,
            'jabatan'               => Jabatan::find($dataKaryawan->jabatan)->nama_jabatan,
            'divisi'                => Divisi::find($dataKaryawan->divisi)->nama_divisi,
            'join_date'             => Carbon::parse($dataKaryawan->join_date)->translatedFormat('d F Y'),
            'end_date'              => Carbon::parse($dataKaryawan->end_date)->translatedFormat('d F Y'),
            'nomor_sio'             => $dataKaryawan->no_sio,
            'tunjangan_jabatan'     => $dataKaryawan->tJabatan,
            'tunjangan_transport'   => $dataKaryawan->tTransport,
            'gol_karyawan'          => $dataKaryawan->gol_karyawan,
            'marital'               => $dataKaryawan->marital,
            'no_npwp'               => $dataKaryawan->no_npwp,
            'no_kpj'                => $dataKaryawan->no_kpj,
            'no_jkn'                => $dataKaryawan->no_jkn
        ];

        return $result;
    }

    function detail_dokumen($id_karyawan){
        $data = Filemanager::where("id_karyawan",$id_karyawan)->get();
        $result = [];
        foreach($data as $key) {
            $result[] = [
                'filename' => $key->filename,
                'path'     => asset($key->path),
                'file'     => $key->slug,
            ];
        }

        return $result;
    }


    function calculate_admin_korlap(Request $request) {
        $id_karyawan = $request->id_karyawan;

        if($id_karyawan == "" || $id_karyawan == null) {
            return response()->json(['pesan' => 'ID Karyawan dibutuhkan'],422);
        }

        $queryID = User::where('id_karyawan',$id_karyawan);

        if($queryID->count() == 0) {
            return response()->json(['pesan' => 'ID Karyawan tidak terdaftar'],401);
        }

        $dataUser = $queryID->first();

        if(!in_array($dataUser->roles,['admin','korlap'])) {
            return response()->json(['pesan' => 'Anda tidak memiliki akses'],401);
        }
        $izin       = DB::table("table_izin as ti")
                            ->join('table_karyawan as us','us.id_karyawan','=','ti.karyawan_id')
                            ->where('ti.status','0')
                            ->where('ti.id_client','LIKE','%'.$dataUser->id_client.'%')
                            ->count();
        $lembur     = DB::table("table_lembur as ti")
                            ->join('table_karyawan as us','us.id_karyawan','=','ti.id_karyawan')
                            ->where('ti.status','0')
                            ->where('ti.id_client','LIKE','%'.$dataUser->id_client.'%')
                            ->count();
        $totalkr    = User::where('id_client',$dataUser->id_client)->where('roles','karyawan')->count();
        $total      = $izin + $lembur;

        $result     = ['total_approval' => $total,'total_karyawan' => $totalkr];

        return response()->json(['data' => $result]);


    }


}

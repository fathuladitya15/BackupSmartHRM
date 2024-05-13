<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Filemanager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadImageController extends Controller
{

    function upload(Request $request) {
        $id_karyawan    = $request->id_karyawan;

        // CEK APABILA ID KARYAWAN ==  NULL
        if($id_karyawan == null) {
            return response()->json(['pesan' => 'ID Karyawan dibutuhkan'],200);
        }

        // CEK ID KARYAWAN TERSEDIA APA TIDAK
        $cekKaryawan    =   $this->cekIDKaryawan($id_karyawan);

        if($cekKaryawan == TRUE){
            if($request->hasFile('image_ktp')) {
               // VALIDASI GAMBAR
                $validasi = Validator::make($request->all(),['image_ktp' => 'image|mimes:jpeg,png,jpg|max:1000'],[
                    'image_ktp.image' => 'File harus berbentuk gambar',
                    'image_ktp.mimes' => 'Gambar yang diizinkan JPEG,PNG,JPG,',
                    'image_ktp.max'   => 'Maksimal ukuran 1 Mb',

                ]);

                // ERROR REPORT
                if ($validasi->fails()) {
                    return response()->json(['pesan' => $validasi->errors()->first()], 400);
                }

                // CEK KETERSDIAAN FILE
                $cekFileExist   = $this->CekFileExists($id_karyawan,'ktp');
                $file           = $request->image_ktp; //REQUEST FILE

                // DATA UNTUK DIUPLOAD
                $dataUpload     = [
                    'id_karyawan'   => $id_karyawan,
                    'file'          => $request->image_ktp,
                    'typeFile'      => 'ktp',
                    'filename'      => "ktp_".date("YmdHi").$id_karyawan.'.'.$file->getClientOriginalExtension(),
                    'keterangan'    => "KTP ID Karyawan:".$id_karyawan,
                    'ext'           => $file->getClientOriginalExtension(),
                ];


                if($cekFileExist == TRUE) {
                    $uploadImage    = $this->imageUpload($dataUpload); //CREATE NEW DB FILEMANAGER BY IDKARYAWAN AND SLUG & UPLOAD IMAGE
                }else {
                    $uploadImage    = $this->imageReUpload($dataUpload); // UPDATE DB FILEMANAGER BY ID KARYAWAN AND SLUG & REUPLOAD IMAGE
                }

            }
            else if($request->hasFile('image_npwp')) {

                // VALIDASI GAMBAR
                $validasi = Validator::make($request->all(),['image_npwp' => 'image|mimes:jpeg,png,jpg|max:1000'],[
                    'image_npwp.image' => 'File harus berbentuk gambar',
                    'image_npwp.mimes' => 'Gambar yang diizinkan JPEG,PNG,JPG,',
                    'image_npwp.max'   => 'Maksimal ukuran 1 Mb',

                ]);

                // ERROR REPORT
                if ($validasi->fails()) {
                    return response()->json(['pesan' => $validasi->errors()->first()], 400);
                }

                // CEK KETERSDIAAN FILE
                $cekFileExist   = $this->CekFileExists($id_karyawan,'npwp');
                $file           = $request->image_npwp; //REQUEST FILE

                // DATA UNTUK DIUPLOAD
                $dataUpload     = [
                    'id_karyawan'   => $id_karyawan,
                    'file'          => $request->image_npwp,
                    'typeFile'      => 'npwp',
                    'filename'      => "npwp_U".date("YmdHi").$id_karyawan.'.'.$file->getClientOriginalExtension(),
                    'keterangan'    => "NPWP ID Karyawan:".$id_karyawan,
                    'ext'           => $file->getClientOriginalExtension(),
                ];


                if($cekFileExist == TRUE) {
                    $uploadImage    = $this->imageUpload($dataUpload); //CREATE NEW DB FILEMANAGER BY IDKARYAWAN AND SLUG & UPLOAD IMAGE
                }else {
                    $uploadImage    = $this->imageReUpload($dataUpload); // UPDATE DB FILEMANAGER BY ID KARYAWAN AND SLUG & REUPLOAD IMAGE
                }

            }
            else if ($request->hasFile('image_kpj')) {
                // VALIDASI GAMBAR
                $validasi = Validator::make($request->all(),['image_kpj' => 'image|mimes:jpeg,png,jpg|max:1000'],[
                    'image_kpj.image' => 'File harus berbentuk gambar',
                    'image_kpj.mimes' => 'Gambar yang diizinkan JPEG,PNG,JPG,',
                    'image_kpj.max'   => 'Maksimal ukuran 1 Mb',

                ]);

                // ERROR REPORT
                if ($validasi->fails()) {
                    return response()->json(['pesan' => $validasi->errors()->first()], 400);
                }

                // CEK KETERSDIAAN FILE
                $cekFileExist   = $this->CekFileExists($id_karyawan,'kpj');
                $file           = $request->image_kpj; //REQUEST FILE

                // DATA UNTUK DIUPLOAD
                $dataUpload     = [
                    'id_karyawan'   => $id_karyawan,
                    'file'          => $request->image_kpj,
                    'typeFile'      => 'kpj',
                    'filename'      => "kpj_".date("YmdHi").$id_karyawan.'.'.$file->getClientOriginalExtension(),
                    'keterangan'    => "KPJ ID Karyawan:".$id_karyawan,
                    'ext'           => $file->getClientOriginalExtension(),
                ];


                if($cekFileExist == TRUE) {
                    $uploadImage    = $this->imageUpload($dataUpload); //CREATE NEW DB FILEMANAGER BY IDKARYAWAN AND SLUG & UPLOAD IMAGE
                }else {
                    $uploadImage    = $this->imageReUpload($dataUpload); // UPDATE DB FILEMANAGER BY ID KARYAWAN AND SLUG & REUPLOAD IMAGE
                }
            }
            else if($request->hasFile('image_jkn')) {
                // VALIDASI GAMBAR
                $validasi = Validator::make($request->all(),['image_jkn' => 'image|mimes:jpeg,png,jpg|max:1000'],[
                    'image_jkn.image' => 'File harus berbentuk gambar',
                    'image_jkn.mimes' => 'Gambar yang diizinkan JPEG,PNG,JPG,',
                    'image_jkn.max'   => 'Maksimal ukuran 1 Mb',

                ]);

                // ERROR REPORT
                if ($validasi->fails()) {
                    return response()->json(['pesan' => $validasi->errors()->first()], 400);
                }

                // CEK KETERSDIAAN FILE
                $cekFileExist   = $this->CekFileExists($id_karyawan,'jkn');
                $file           = $request->image_jkn; //REQUEST FILE

                // DATA UNTUK DIUPLOAD
                $dataUpload     = [
                    'id_karyawan'   => $id_karyawan,
                    'file'          => $request->image_jkn,
                    'typeFile'      => 'jkn',
                    'filename'      => "jkn_".date("YmdHi").$id_karyawan.'.'.$file->getClientOriginalExtension(),
                    'keterangan'    => "JKN ID Karyawan:".$id_karyawan,
                    'ext'           => $file->getClientOriginalExtension(),
                ];


                if($cekFileExist == TRUE) {
                    $uploadImage    = $this->imageUpload($dataUpload); //CREATE NEW DB FILEMANAGER BY IDKARYAWAN AND SLUG & UPLOAD IMAGE
                }else {
                    $uploadImage    = $this->imageReUpload($dataUpload); // UPDATE DB FILEMANAGER BY ID KARYAWAN AND SLUG & REUPLOAD IMAGE
                }
            }
            else if($request->hasFile('image_cv')) {
                // VALIDASI FILE
                $validasi = Validator::make($request->all(),['image_cv' => 'image|mimes:jpeg,png,jpg,pdf|max:1000'],[
                    'image_cv.image' => 'File harus berbentuk gambar',
                    'image_cv.mimes' => 'Gambar yang diizinkan JPEG,PNG,JPG dan PDF',
                    'image_cv.max'   => 'Maksimal ukuran 1 Mb',

                ]);

                // ERROR REPORT
                if ($validasi->fails()) {
                    return response()->json(['pesan' => $validasi->errors()->first()], 400);
                }

                // CEK KETERSDIAAN FILE
                $cekFileExist   = $this->CekFileExists($id_karyawan,'cv');
                $file           = $request->image_cv; //REQUEST FILE

                // DATA UNTUK DIUPLOAD
                $dataUpload     = [
                    'id_karyawan'   => $id_karyawan,
                    'file'          => $request->image_cv,
                    'typeFile'      => 'cv',
                    'filename'      => "cv_".date("YmdHi").$id_karyawan.'.'.$file->getClientOriginalExtension(),
                    'keterangan'    => "CV ID Karyawan:".$id_karyawan,
                    'ext'           => $file->getClientOriginalExtension(),
                ];


                if($cekFileExist == TRUE) {
                    $uploadImage    = $this->imageUpload($dataUpload); //CREATE NEW DB FILEMANAGER BY IDKARYAWAN AND SLUG & UPLOAD IMAGE
                }else {
                    $uploadImage    = $this->imageReUpload($dataUpload); // UPDATE DB FILEMANAGER BY ID KARYAWAN AND SLUG & REUPLOAD IMAGE
                }
            }
            else if($request->hasFile('dokumen_lainnya')) {
                // VALIDASI FILE
                $validasi = Validator::make($request->all(),['dokumen_lainnya' => 'mimes:pdf|max:1000'],[
                    'dokumen_lainnya.mimes' => 'File harus dalam format PDF, dan digabungkan jika file lebih dari 1',
                    'dokumen_lainnya.max'   => 'Maksimal ukuran 1 Mb',
                ]);

                // ERROR REPORT
                if ($validasi->fails()) {
                    return response()->json(['pesan' => $validasi->errors()->first()], 400);
                }

                // CEK KETERSDIAAN FILE
                $cekFileExist   = $this->CekFileExists($id_karyawan,'jkn');
                $file           = $request->dokumen_lainnya; //REQUEST FILE

                // DATA UNTUK DIUPLOAD
                $dataUpload     = [
                    'id_karyawan'   => $id_karyawan,
                    'file'          => $request->dokumen_lainnya,
                    'typeFile'      => 'lainnya',
                    'filename'      => "docLainnya_".date("YmdHi").$id_karyawan.'.'.$file->getClientOriginalExtension(),
                    'keterangan'    => "Dokumen Pendukung ID Karyawan:".$id_karyawan,
                    'ext'           => $file->getClientOriginalExtension(),
                ];

                if($cekFileExist == TRUE) {
                    $uploadImage    = $this->imageUpload($dataUpload); //CREATE NEW DB FILEMANAGER BY IDKARYAWAN AND SLUG & UPLOAD IMAGE
                }else {
                    $uploadImage    = $this->imageReUpload($dataUpload); // UPDATE DB FILEMANAGER BY ID KARYAWAN AND SLUG & REUPLOAD IMAGE
                }
            }
            else if($request->hasFile('photo_profile')) {
                 // VALIDASI GAMBAR
                 $validasi = Validator::make($request->all(),['photo_profile' => 'image|mimes:jpeg,png,jpg|max:1000'],[
                    'photo_profile.image' => 'File harus berbentuk gambar',
                    'photo_profile.mimes' => 'Gambar yang diizinkan JPEG,PNG,JPG,',
                    'photo_profile.max'   => 'Maksimal ukuran 1 Mb',

                ]);

                // ERROR REPORT
                if ($validasi->fails()) {
                    return response()->json(['pesan' => $validasi->errors()->first()], 400);
                }

                // CEK KETERSDIAAN FILE
                $cekFileExist   = $this->CekFileExists($id_karyawan,'foto_profile');
                $file           = $request->photo_profile; //REQUEST FILE

                // DATA UNTUK DIUPLOAD
                $dataUpload     = [
                    'id_karyawan'   => $id_karyawan,
                    'file'          => $request->photo_profile,
                    'typeFile'      => 'photo_profile',
                    'filename'      => "PP_".date("YmdHi").$id_karyawan.'.'.$file->getClientOriginalExtension(),
                    'keterangan'    => "Foto Profile:".$id_karyawan,
                    'ext'           => $file->getClientOriginalExtension(),
                ];

                if($cekFileExist == TRUE) {
                    $uploadImage    = $this->imageUpload($dataUpload); //CREATE NEW DB FILEMANAGER BY IDKARYAWAN AND SLUG & UPLOAD IMAGE
                }else {
                    $uploadImage    = $this->imageReUpload($dataUpload); // UPDATE DB FILEMANAGER BY ID KARYAWAN AND SLUG & REUPLOAD IMAGE
                }
                return response()->json(['data' => $uploadImage]);
            }

            if($request->nomor_ktp) {
                $validasi = Validator::make($request->all(),[
                    'nomor_ktp'         => 'digits:16|unique:table_karyawan,nik'
                ],[
                    'nomor_ktp.digits'   => 'Nomor KTP harus 16 Angka',
                    'nomor_ktp.unique'   => 'Nomor KTP anda telah terdaftar',
                ]);
    
                if ($validasi->fails()) {
                    return response()->json(['pesan' => $validasi->errors()->first()], 400);
                }
            }

            $getKaryawan            = Karyawan::where('id_karyawan',$id_karyawan)->first();
            if($request->nomor_ktp != null ) {
                $getKaryawan->nik       = $request->nomor_ktp;
            }
            $getKaryawan->no_npwp   = $request->nomor_npwp;
            $getKaryawan->no_jkn    = $request->nomor_jkn;
            $getKaryawan->no_kpj    = $request->nomor_kpj;
            $getKaryawan->save();
            return response()->json(['pesan' => 'Data Karyawan telah diperbaharui',],200);
        }else {
            return response()->json(['pesan' => 'ID Karawan tidak terdaftar'],404);
        }

    }

    function uploadFotoProfile(Request $request) {
        if($request->id_karyawan == "" || $request->id_karyawan == null) {
            return response()->json(['pesan' => 'ID Karyawan tidak diketahui'],404);
        }
        
        $cekFileExist   = $this->CekFileExists($request->id_karyawan,'foto_profile');

        // return response()->json(['base64' => base64_decode($request->input('photo_profile'))],200);
        if($cekFileExist == FALSE) {
            $getPathOld         = Filemanager::where('id_karyawan',$request->id_karyawan)->where('slug','foto_profile')->first()->path; // Ambil path sebelumya dari database
            $getPathServer      = public_path().$getPathOld;    //Kombinasikan dengan path server 

            unlink($getPathServer); // Unlink / Hapus File sebelumnya
            $saveImageBase64    = $this->convertBase64ToJpg($request); // Save gambar baru


            $updateData     = [
                'path'      => $saveImageBase64['path'],
                'filename'  => $saveImageBase64['filename'],
            ];

            // GET ID FILE MANAGER            
            $idFilemanager          = Filemanager::where('id_karyawan',$request->id_karyawan)->where('slug','foto_profile')->first()->id;

            // PROSES UPDATE DATA
            $UpdateFile             = Filemanager::find($idFilemanager);
            $UpdateFile->path       = $updateData['path'];
            $UpdateFile->filename   = $updateData['filename'];
            $UpdateFile->update();

            return response()->json(['pesan' => 'Foto profile diperbaharui'],200);
        }else {
            // SAVE GAMBAR  
            $saveImageBase64        = $this->convertBase64ToJpg($request);

            // DATA INSERT TO DATABASE
            $dataInsert             = [
                'filename'          => $saveImageBase64['filename'],
                'path'              => $saveImageBase64['path'],
                'keterangan'        => 'Foto Profile ID:'.$request->id_karyawan,
                'slug'              => 'foto_profile',
                'id_karyawan'       => $request->id_karyawan,
                'extension'         => 'jpg',
            ];

            // SAVE TO DATABASE
            Filemanager::create($dataInsert);
            return response()->json(['pesan' => 'Foto Profile berhasil ditambahkan'],200);
        }

        return response()->json(['data' => $getPathServer]);


    }

    function cekIDKaryawan($id) {
        $user = User::where('id_karyawan',$id)->first();
        if(!$user) {
            return FALSE;
        }
        return TRUE;

    }

    function CekFileExists($id,$nameFile) {
        $cek    = Filemanager::where('id_karyawan',$id)->where('slug',$nameFile)->first();
        if($cek) {
           return FALSE;
        }else {
            return TRUE;
        }
    }

    function imageUpload($data) {
        $dataInsert = [
            "file"          => $data['file'],
            "filename"      => $data['filename'],
            "path"          => "/filemanager/".$data['typeFile'].'/'.$data['filename'],
            "extension"     => $data['ext'],
            "id_karyawan"   => $data['id_karyawan'],
            "slug"          => $data['typeFile'] == 'photo_profile' ? 'foto_profile' : $data['typeFile'] ,
            "keterangan"    => $data['keterangan'],
            "status"        => 'Create New',
        ];

        // SIMPAN FILE TO PUBLIC
        $data['file']->move(public_path("/filemanager/".$data['typeFile']),$data['filename']);

        // SIMPAN DATA TO DATABSE
        Filemanager::create($dataInsert);
        return "Data telah ditambahkan";
    }

    function imageReUpload($data) {

        $dataInsert = [
            "filename"      => $data['filename'],
            "path"          => "/filemanager/".$data['typeFile'].'/'.$data['filename'],
            "extension"     => $data['ext'],
            "id_karyawan"   => $data['id_karyawan'],
            "slug"          => $data['typeFile'] == 'photo_profile' ? 'foto_profile' : $data['typeFile'],
            "keterangan"    => $data['keterangan'],
            "status"        => 'Re Upload',
        ];

        $slugs = $data['typeFile'] == 'photo_profile' ? 'foto_profile' : $data['typeFile'];

        $getDatabase    = Filemanager::where('id_karyawan',$data['id_karyawan'])->where('slug',$slugs)->first();
        // return $dataInsert;
        $pathOld        = public_path().$getDatabase->path;
        unlink($pathOld);
        if($getDatabase) {
            $getDatabase->path     = "/filemanager/".$data['typeFile'].'/'.$data['filename'];
            $getDatabase->filename = $data['filename'];
            $getDatabase->save();
        }
        $data['file']->move(public_path("/filemanager/".$data['typeFile']),$data['filename']);

        return 'Data Sudah diperbaharui';
    }

    public function convertBase64ToJpg($request)
    {

        $id_karyawan    = $request->id_karyawan;
        $base64Image    = $request->input('photo_profile');
    
        // Decode Base64 menjadi data biner
        // $imageData   = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
        $imageData      = base64_decode($base64Image);

        // Tentukan lokasi penyimpanan dan nama file
        $fileName       = 'PP_' . time().$id_karyawan. '.jpg';
        $storagePath    = public_path('/filemanager/photo_profile/' . $fileName); // Tentukan lokasi penyimpanan

        file_put_contents($storagePath, $imageData);


        $r = [
            'filename'  => $fileName,
            'path'      => '/filemanager/photo_profile/'.$fileName,
        ];

        return $r;
    }
    
}

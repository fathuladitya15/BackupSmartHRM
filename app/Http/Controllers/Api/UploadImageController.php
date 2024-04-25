<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Filemanager;
use App\Models\Karyawan;
use Validator;

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

            // VALIDASI NIK
            $validasi = Validator::make($request->all(),[
                'nomor_ktp'         => 'digits:16|unique:table_karyawan,nik'
            ],[
                'nomor_ktp.digits'   => 'Nomor KTP harus 16 Angka',
                'nomor_ktp.unique'   => 'Nomor KTP anda telah terdaftar',
            ]);

            // RETURN REPORT NIK
            if ($validasi->fails()) {
                return response()->json(['pesan' => $validasi->errors()->first()], 400);
            }

            // UPDATE DATA
            $getKaryawan            = Karyawan::where('id_karyawan',$id_karyawan)->first();
            $getKaryawan->nik       = $request->nomor_ktp;
            $getKaryawan->no_npwp   = $request->nomor_npwp;
            $getKaryawan->no_jkn    = $request->nomor_jkn;
            $getKaryawan->no_kpj    = $request->nomor_kpj;
            $getKaryawan->save();
            return response()->json(['pesan' => 'Data Karyawan telah diperbaharui',],200);
        }else {
            return response()->json(['pesan' => 'ID Karawan tidak terdaftar'],404);
        }

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
            "path"          => "/filemanager/".$data['typeFile'],
            "extension"     => $data['ext'],
            "id_karyawan"   => $data['id_karyawan'],
            "slug"          => $data['typeFile'],
            "keterangan"    => $data['keterangan'],
            "status"        => 'Create New',
        ];

        // SIMPAN FILE TO PUBLIC
        $data['file']->move(public_path($dataInsert['path']),$data['filename']);

        // SIMPAN DATA TO DATABSE
        Filemanager::create($dataInsert);
        return "Data telah ditambahkan";
    }

    function imageReUpload($data) {

        $dataInsert = [
            "filename"      => $data['filename'],
            "path"          => "/filemanager/".$data['typeFile'],
            "extension"     => $data['ext'],
            "id_karyawan"   => $data['id_karyawan'],
            "slug"          => $data['typeFile'],
            "keterangan"    => $data['keterangan'],
            "status"        => 'Re Upload',
        ];

        $getDatabase    = Filemanager::where('id_karyawan',$data['id_karyawan'])->where('slug',$data['typeFile'])->first();
        $pathOld        = public_path().$getDatabase->path.'/'.$getDatabase->filename;
        unlink($pathOld);
        if($getDatabase) {

            $getDatabase->filename = $data['filename'];
            $getDatabase->save();
        }
        $data['file']->move(public_path($dataInsert['path']),$data['filename']);

        return 'Data Sudah diperbaharui';
    }

}

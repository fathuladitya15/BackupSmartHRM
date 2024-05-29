<?php

use App\Http\Controllers\Api\ReferensikerjaController;
use App\Http\Controllers\Api\UploadImageController;
use App\Http\Controllers\Api\PreorderController;
use App\Http\Controllers\Api\KaryawanController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AbsensiController;
use App\Http\Controllers\Api\LemburController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\CutiController;
use App\Http\Controllers\Api\IzinController;
use App\Http\Controllers\Api\PengumumanController;
use App\Http\Controllers\Api\PeringatanController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Filemanager;
use App\Models\Karyawan;
use App\Models\Bank;

// use App\Models\Filemanager;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('ttd')->group(function() {
    Route::get('cek-ttd',function(Request $request) {
        $search = Filemanager::where('id_karyawan',$request->id_karyawan)->where('slug','signature')->count();
        if($search > 0) {
            $data = Filemanager::where('id_karyawan',$request->id_karyawan)->where('slug','signature')->first();
            $result = [
                'ttd' => asset($data->path),
            ];
            return response()->json(['data'  => $result],200);
        }else {
            return redirect()->route('create-tanda-tangan-m',['id_karyawan' => $request->id_karyawan]);

        }
    })->name('cek-ttd');

});

Route::get('get-bank', function() {
    $data  = Bank::all();
    if($data) {
        return response()->json(['data' => $data], 200);
    }
    return response()->json(['data' => $data], 404);

});

Route::prefix('cuti')->group(function() {
    Route::get('get-data-cuti',[CutiController::class,'get_data_cuti']);
    Route::post('create-cuti-internal',[CutiController::class,'create_cuti_internal']);
    Route::get('get-data-cuti-manager',[CutiController::class,'get_data_cuti_manager']);
    Route::get('get-data-cuti-hrd',[CutiController::class,'get_data_cuti_spv_hrd']);
    Route::get('get-data-cuti-dir-hrd',[CutiController::class,'get_data_cuti_dir_hrd']);
    Route::post('update',[CutiController::class,'update_status']);
    Route::get('kategori-cuti',[CutiController::class,'get_kategori_cuti']);
    Route::get('file/{idCuti}',[CutiController::class,'viewFile'])->name("download-cuti");
    Route::get('admin-korlap',[CutiController::class,'getDataCutiAdmin']);



});

Route::post('login', [LoginController::class, 'login']);
Route::get('lembur',[LemburController::class,'get_data_lembur']);

Route::prefix('lembur')->group(function() {
    Route::post('create',[LemburController::class,'create_data_lembur']);
    Route::post('update-internal',[LemburController::class,'update_data_lembur_internal']);
    Route::get('spv-internal',[LemburController::class,'get_data_lembur_spv_internal']);
    Route::get('spv-hrd',[LemburController::class,'get_data_lembur_spv_hrd']);
    Route::get('dir-hrd',[LemburController::class,'get_data_lembur_dir_hrd']);
    Route::get('manager',[LemburController::class,'get_data_lembur_manager']);
    Route::get('data-shift',[LemburController::class,'getShift']);
    Route::get('file/{id}',[LemburController::class,'viewFile'])->name('download-lembur');
    Route::get('shift/{id_client}',[LemburController::class,'getDataShift']);
    Route::post('create-karyawan',[LemburController::class,'createLemburKrExternal']);
    Route::get('admin-korlap',[LemburController::class,'getDataLemburAdmin']);
});

Route::prefix('izin')->group(function() {
    Route::post('create',[IzinController::class,'create_izin']);
    Route::post('update',[IzinController::class,'update']);
    Route::get('data-izin-internal',[IzinController::class,'get_izin_karyawan']);
    Route::get('data-izin-manager',[IzinController::class,'get_izin_manager']);
    Route::get('data-izin-spv-hrd',[IzinController::class,'get_izin_hrd']);
    Route::get('data-izin-direktur-hrd',[IzinController::class,'get_izin_direktur_hrd']);
    Route::get('file/{id}',[IzinController::class,'viewFile'])->name('download-izin');
    route::get('data-izin-adm',[IzinController::class,'getDataIzinAdmin']);

});

Route::prefix('absensi')->group(function() {
    Route::get('data-karyawan',[AbsensiController::class,'get_data_absensi']);
    Route::post('clock-in',[AbsensiController::class,'create_absensi']);
    Route::post('clock-out',[AbsensiController::class,'update_absensi']);
    Route::get('data-clients',[AbsensiController::class,'get_data_client']);
    Route::get('cek-absen',[AbsensiController::class,'cek_absensi']);
    Route::get('absen',[AbsensiController::class,'absensi']);
    Route::get('filter',[AbsensiController::class,'filterData']);
    Route::get('hrd',[AbsensiController::class,'data_absensi_hrd']);
    Route::get('adm/{gol_karyawan}',[AbsensiController::class,'get_absensi_admin_korlap']);

});

Route::prefix('preorder')->group(function() {
    Route::get('data',[PreorderController::class,'dataPreOrder']);
    Route::post('acc-order',[PreorderController::class,'acc_spv_internal']);
    Route::post('ttd-order',[PreorderController::class,'ttd_direktur']);
    Route::get('file/{id}',[PreorderController::class,'viewFile'])->name('download-preorder');
    Route::get('data-admin-korlap',[PreorderController::class,'getPreOrderAdmin']);
    Route::post('create-admin-korlap',[PreorderController::class,'createPreOrderAdmin']);
    Route::get('data-ga',[PreorderController::class,'getDataGA']);

});

route::prefix('upload')->group(function() {
    Route::post('image',[UploadImageController::class,'upload']);
    Route::post('image/profile',[UploadImageController::class,'uploadFotoProfile']);
});

Route::prefix('rf')->group(function() {
    Route::get('data',[ReferensikerjaController::class,'get_data']);
    Route::post('update',[ReferensiKerjaController::class,'update_status']);
    Route::get('data-admin',[ReferensiKerjaController::class,'getReferensiKerjaAdmin']);
    Route::post('create-data-admin',[ReferensiKerjaController::class,'createReferensiKerja']);
});

Route::prefix('karyawan')->group(function() {
    Route::post('create',[KaryawanController::class,'create']);
    Route::get('tipe-akun',[KaryawanController::class,'get_tipe_akun']);
    Route::get('kategori',[KaryawanController::class,'kategori_karyawan']);
    Route::get('client',[KaryawanController::class,'clients']);
    Route::get('jabatan',[KaryawanController::class,'jabatan']);
    Route::get('divisi',[KaryawanController::class,'divisi']);
    Route::get('marital',[KaryawanController::class,'marital']);
    Route::get('calculate',[KaryawanController::class,'calculate']);
    Route::get('data/{roles}',[KaryawanController::class,'get_dataKaryawan']);
    Route::get('calculate-adm',[KaryawanController::class,'calculate_admin_korlap']);
});

Route::prefix('pengumuman')->group(function() {
    Route::get('get-admin',[PengumumanController::class,'getPengumumanAdmin']);
});

Route::prefix('peringatan')->group(function() {
    Route::get('get-admin',[PeringatanController::class,'getPeringatanAdmin']);
    Route::post('create-admin',[PeringatanController::class,'createPeringatanAdmin']);
});

route::middleware(['auth'])->group(function() {
    route::get('/home', function() {
        return response()->json(Auth::user()->name);
    });
});

Route::post('save-ttd',[AbsensiController::class,'save_ttd_mobile']);
Route::get('profile',[ProfileController::class,'index']);
Route::get('convert-svg',[LemburController::class,'convertSvgToPng']);
Route::post('update-password',[ProfileController::class,'updatePassword']);


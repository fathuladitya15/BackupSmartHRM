<?php

use Illuminate\Support\Facades\Route;

use App\Models\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\PeringatanController;
use App\Http\Controllers\ManageKaryawanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::post('/search-bank',function(Request $request) {
    $search = Bank::where('nama_bank',$request->nama_bank)->first();
    if($search) {
        $message = ['status' => TRUE, 'search' => $search, 'pesan' => 'Kode Bank berhasil ditemukan'];
    }else {
        $message = ['status' => TRUE, 'search' => $search, 'pesan' => 'Kode Bank Tidak ditemukan'];
    }
    return response()->json($message);
})->name("cari-bank");

Route::middleware('revalidate')->group(function() {
    Route::middleware('SingelSession')->group(function() {
        Route::middleware('auth')->group(function() {
            Route::get('/home', [HomeController::class, 'index'])->name('home');

            Route::get('/karyawan',[ManageKaryawanController::class,'index'])->name('karyawan');
            Route::get('/karyawan/add',[ManageKaryawanController::class,'create'])->name('karyawan-add');
            Route::post('/karyawan/process',[ManageKaryawanController::class,'store'])->name('karyawan-saving');
            Route::get('/karyawan/detail/{hash}',[ManageKaryawanController::class,'edit'])->name('karyawan-edit');
            Route::get('/karyawan/detail/{hash}/data-pribadi',[ManageKaryawanController::class,'pribadi'])->name('karyawan-pribadi');
            Route::get('/karyawan/detail/{hash}/data-perusahaan',[ManageKaryawanController::class,'client'])->name('karyawan-client');
            Route::get('/karyawan/detail/{hash}/data-dokumen',[ManageKaryawanController::class,'dokumen'])->name('karyawan-dokumen');
            Route::get('/karyawan/detail/{hash}/data-bank',[ManageKaryawanController::class,'bank'])->name('karyawan-bank');
            Route::post('/karyawan/detail/{hash}/update',[ManageKaryawanController::class,'update_data'])->name("karyawan-update");
            Route::post('/karyawan/detail/{hash}/view_file',[ManageKaryawanController::class,'view_file'])->name("karyawan-view-file");

            Route::get('/Jabatan',[JabatanController::class,'index'])->name('jabatan');
            Route::get('/Jabatan/data',[JabatanController::class,'data'])->name('jabatan-data');
            Route::post('/Jabatan/saving',[JabatanController::class,'store'])->name('jabatan-save');
            Route::post('/Jabatan/get',[JabatanController::class,'get_data'])->name('jabatan-get');
            Route::delete('/Jabatan/delete',[JabatanController::class,'delete_data'])->name('jabatan-delete');

            Route::get('/Divisi',[DivisiController::class,'index'])->name('divisi');
            Route::get('/Divisi/data',[DivisiController::class,'data'])->name('divisi-data');
            Route::post('/Divisi/saving',[DivisiController::class,'store'])->name('divisi-save');
            Route::post('/Divisi/get',[DivisiController::class,'get_data'])->name('divisi-get');
            Route::delete('/Divisi/delete',[DivisiController::class,'delete_data'])->name('divisi-delete');

            Route::get('/shift',[ShiftController::class,'index'])->name('shift');
            Route::get('/shift/data',[ShiftController::class,'data'])->name('shift-data');
            Route::post('/shift/saving',[ShiftController::class,'save_data'])->name('shift-save');
            Route::post('/shift/get',[ShiftController::class,'get_data'])->name('shift-get');
            Route::delete('/shift/delete',[ShiftController::class,'delete_data'])->name('shift-delete');

            Route::get('/lembur',[LemburController::class,'index'])->name('lembur');
            Route::get('/lembur-self',[LemburController::class,'index_self'])->name('lembur-self');
            Route::post('/lembur/save',[LemburController::class,'validasi'])->name('lembur-save');
            Route::post('/lembur/avv',[LemburController::class,'accepted'])->name('lembur-acc');
            Route::get('/lembur/{hash}',[DatatableController::class,'data_lembur_karyawan'])->name('lembur-data');
            Route::get('/lembur/{hash}/admin-korlap',[DatatableController::class,'data_lembur_karyawan_admin'])->name('lembur-admin-korlap');
            Route::get('/lembur/{hash}/spv-internal',[DatatableController::class,'data_lembur_karyawan_spv'])->name('lembur-spv-internal');
            Route::post('/lembur/detail',[LemburController::class,'detail'])->name('lembur-detail');
            Route::post('/lembur/download-data',[LemburController::class,'download_data'])->name('lembur-download-data');
            Route::get('/lembur/download-data-perorang/{hash}',[LemburController::class,'download_perorang'])->name('lembur-download-perorang');
            Route::post('/lembur/download-data-multipe/{hash}',[LemburController::class,'download_multiple'])->name('lembur-download-multiple');
            Route::post('/lembur/upload-files',[LemburController::class,'upload_files'])->name('lembur-upload');
            Route::post('/lembur/get-files',[LemburController::class,'get_files'])->name('lembur-get-files');
            Route::post('/lember/send-files',[LemburController::class,'acc_data'])->name('lembur-kirim-files');


            Route::get('/surat-peringatan',[PeringatanController::class,'index'])->name('peringatan');
            Route::post('/surat-peringatan/details-karyawan',[PeringatanController::class,'get_detail_karyawan'])->name("peringatan-detail-karyawan");

            Route::get('/izin',[IzinController::class,'index'])->name('izin');
            Route::get('/izin-self',[IzinController::class,'self'])->name('izin-self');
            Route::post('/izin',[IzinController::class,'saving'])->name('izin-save');
            Route::get('/izin-get',[DatatableController::class,'data_izin_karyawan'])->name('izin-data');
            Route::get('/izin-get-adm',[DatatableController::class,'data_izin_admin_korlap'])->name('izin-data-admin-korlap');
            Route::post('/izin-acc',[IzinController::class,'data_izin_accept'])->name('izin-acc');
            Route::post('/izin-detail',[IzinController::class,'detail'])->name('izin-detail');
            Route::post('/izin-upload',[IzinController::class,'upload'])->name('izin-upload');
            Route::post('/izin-file',[IzinController::class,'get_files'])->name('izin-file');
            Route::get('/izin-download/{hash}',[IzinController::class,'download_file'])->name('izin-download');


            Route::get('/produk',[ProdukController::class,'index'])->name('produk');


            Route::middleware(['role:superadmin'])->group(function(){
                Route::get('/karyawan/data-superadmin',[DatatableController::class,'data_karyawan_superadmin'])->name('data-kr-superadmin');

                Route::get('/Clients',[ClientsController::class,'index'])->name('client');
                Route::get('/Clients/data',[ClientsController::class,'data'])->name('client-data');
                Route::post('/Clients/saving',[ClientsController::class,'store'])->name('client-save');

                Route::get('/Roles',[SuperadminController::class,'roles_index'])->name('roles');
                Route::get('/Roles/data',[SuperadminController::class,'roles_data'])->name('roles-data');
                Route::post('/Roles/saving',[SuperadminController::class,'roles_save'])->name('roles-save');
                Route::post('/Roles/get',[SuperadminController::class,'roles_get'])->name('roles-get');
                Route::delete('/Roles/delete',[SuperadminController::class,'roles_delete'])->name('roles-delete');
            });

            Route::middleware(['role:hrd'])->group(function() {
                Route::get('/karyawan/data-hrd',[DatatableController::class,'data_karyawan_hrd'])->name('data-kr-hrd');
            });

            Route::middleware(['role:korlap:admin:hrd'])->group(function() {
                Route::get('/karyawan/data-admin',[DatatableController::class,'data_karyawan_admin'])->name('data-kr-admin');
            });
            Route::post('/check-tanda-tangan',[HomeController::class,'checking_tanda_tangan'])->name('check-tanda-tangan');
            Route::post('/check-tanda-tangan/save',[HomeController::class,'save_tanda_tangan'])->name('save-tanda-tangan');
        });

    });

});

<?php

use Illuminate\Support\Facades\Route;

use App\Models\Bank;
// App\Http\Controllers\Auth\
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ConfirmPassword;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;


use Illuminate\Http\Request;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\DatatableController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\PeringatanController;
use App\Http\Controllers\ReferensiKerjaController;
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

// Auth::routes();

Route::get('/login',[LoginController::class,'showLoginForm'])->name('login');
Route::post('/login',[LoginController::class,'login'])->name('login');
Route::post('/logout',[LoginController::class,'logout'])->name('logout');

Route::post('/search-bank',function(Request $request) {
    $search = Bank::where('nama_bank',$request->nama_bank)->first();
    if($search) {
        $message = ['status' => TRUE, 'search' => $search, 'pesan' => 'Kode Bank berhasil ditemukan'];
    }else {
        $message = ['status' => TRUE, 'search' => $search, 'pesan' => 'Kode Bank Tidak ditemukan'];
    }
    return response()->json($message);
})->name("cari-bank");

Route::get('/times', function(Request $request) {
    $now = Carbon\Carbon::now()->translatedFormat('l, d F Y / H:i');

    return response()->json($now) ;
})->name("times");

Route::get('/camera-test', function() {
    return view('layouts.test-camera');
});

Route::middleware('revalidate')->group(function() {
    Route::middleware('SingelSession')->group(function() {
        Route::middleware('auth')->group(function() {
            Route::get('/home', [HomeController::class, 'index'])->name('home');

            Route::get('/absen',[AbsensiController::class,'cek'])->name("absensi");
            Route::get('/Absensi/data/',[DatatableController::class,'absensi_karyawan'])->name('absensi-data-karyawan');
            Route::get('/Absensi/{karyawan}',[AbsensiController::class,'index'])->name('absensi-data');
            Route::get('/Absensi/korlap/{gol_karyawan}',[DatatableController::class,'absensi_korlap'])->name("absensi-korlap");
            Route::get('/Absensi/{karyawan}/data',[DatatableController::class,'absensi_hrd'])->name('absensi-data-ajax');
            Route::get('/Absensi/{karyawan}/search/{id}',[AbsensiController::class,'search_by_one'])->name('absensi-search-one');
            Route::post('/Absensi/data/search/dokumen',[AbsensiController::class,'dokumen_perorang'])->name('absensi-doc-perorang');

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

            Route::get('/lembur/data-lembur-manajer/data',[DatatableController::class,'data_lembur_karyawan_manajer'])->name('lembur-manajer');

            Route::get('/surat-peringatan',[PeringatanController::class,'index'])->name('peringatan');
            Route::post('/surat-peringatan/update-karyawan',[PeringatanController::class,'get_detail_karyawan'])->name("peringatan-detail-karyawan");
            Route::post('/surat-peringatan/save',[PeringatanController::class,'save'])->name('peringatan-save');
            Route::get('/surat-peringatan/data',[DatatableController::class,'data_peringatan_admin'])->name('peringatan-data');
            Route::post('/surat-peringatan/details',[PeringatanController::class,'details'])->name('peringatan-detail');
            Route::post('/surat-peringatan/update',[PeringatanController::class,'update'])->name('peringatan-update');
            route::get('/surat-peringatan/files/{id}',[PeringatanController::class,'files'])->name('peringatan-file');


            Route::get('/surat-referensi-kerja',[ReferensiKerjaController::class,'index'])->name('index-rf');
            Route::post('/surat-referensi-kerja/save',[ReferensiKerjaController::class,'save'])->name('save-rf');
            Route::get('/surat-referensi-kerja/data',[DatatableController::class,'data_referensi_kerja'])->name('data-rf');
            Route::post('/surat-referensi-kerja/update',[ReferensiKerjaController::class,'update'])->name('update-rf');
            Route::post('/surat-referensi-kerja/detail',[ReferensiKerjaController::class,'detail'])->name('detail-rf');
            Route::get('/surat-referensi-kerja/dokumen/{id}',[ReferensiKerjaController::class,'dokumen'])->name('dokumen-rf');

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


            Route::get('/cuti',[CutiController::class,'index'])->name('cuti');
            Route::post('/cuti-detail-data',[CutiController::class,'cuti_data'])->name('cuti-detail');
            Route::post('/cuti/get-data-cuti',[CutiController::class,'detail_data_cuti'])->name("cuti-details-data");
            Route::post('/cuti/save',[CutiController::class,'save_cuti'])->name('cuti-save');
            Route::post('/cuti/validasi-cuti',[CutiController::class,'validasi_tanggal_cuti'])->name('cuti-validasi-cuti');
            Route::get('/cuti/dokumen/{id}',[CutiController::class,'dokumen_cuti'])->name('dokumen-cuti');


            Route::get('/cuti-kategori',[CutiController::class,'kategori_cuti'])->name('cuti-kategori');
            Route::post('/cuti-kategori-save',[CutiController::class,'kategori_cuti_save'])->name('cuti-kategori-save');
            Route::post('/cuti-kategori-get',[CutiController::class,'kategori_cuti_get'])->name('cuti-kategori-get');
            Route::delete('/cuti-kategori-delete',[CutiController::class,'kategori_cuti_delete'])->name('cuti-kategori-delete');

            Route::get('/cuti-kategori-data',[DatatableController::class,'data_kategori_cuti'])->name('cuti-kategori-data');
            Route::get('/cuti/data-karyawan',[DatatableController::class,'data_cuti_karyawan'])->name('data-cuti-karyawan');
            Route::get('/cuti/data-manajer',[DatatableController::class,'data_cuti_manajer'])->name('data-cuti-manajer');
            Route::get('/cuti/data-direktur',[DatatableController::class,'data_cuti_direktur'])->name('data-cuti-direktur');


            Route::middleware(['role:karyawan'])->group(function(){
                Route::get('/surat-peringatan/data-karyawan',[DatatableController::class,"data_peringatan_karyawan"])->name('peringatan-data-kr');
            });

            Route::middleware(['role:spv-internal'])->group(function(){
                Route::get('/surat-peringatan/data-spv',[DatatableController::class,'data_peringatan_spv'])->name('peringatan-data-spv');

            });
            Route::middleware(['role:admin:korlap'])->group(function() {
                Route::post('/surat-peringatan/get-karyawan',[PeringatanController::class,'data_karyawan'])->name("peringatan-data-karyawan");
                Route::get('/surat-peringatan/data-admin',[DatatableController::class,'data_peringatan_admin'])->name('peringatan-data-admin');

                // LIST PRODUK

                Route::get('/list-produk',[ProdukController::class,'list_produk'])->name('list-produk');
                Route::post('/list-produk/upload',[ProdukController::class,'upload_list_produk'])->name('list-produk-upload');
                Route::get('/list-produk/data/{id_client}',[DataTableController::class,'data_list_produk'])->name('list-produk-data');
                Route::post('/list-produk/get',[ProdukController::class,'get_list_produk'])->name('list-produk-get');
                Route::post('/list-produk/update',[ProdukController::class,'update_list_produk'])->name('list-produk-update');
                Route::post('/list-produk/add',[ProdukController::class,'add_list_produk'])->name('list-produk-add');
                Route::delete('/list-produk/delete',[ProdukController::class,'delete_list_produk'])->name('list-produk-delete');

                Route::get('/laporan-produksi',[ProdukController::class,'laporan_produksi'])->name("laporan-produksi");
                Route::get('/laporan-produksi/data',[DatatableController::class,'data_laporan_produksi'])->name("laporan-produksi-data");
                Route::post('/laporan-produksi/add',[ProdukController::class,'add_laporan_produksi'])->name("laporan-produksi-add");
                Route::post('/laporan-produksi/get',[ProdukController::class,'get_laporan_produksi'])->name("laporan-produksi-get");
                Route::post('/laporan-produksi/update',[ProdukController::class,'update_laporan_produksi'])->name("laporan-produksi-update");
                Route::delete('/laporan-produksi/delete',[ProdukController::class,'delete_laporan_produksi'])->name("laporan-produksi-delete");
                Route::get('/laporan-produksi/datas',[ProdukController::class,'data_laporan_produksi'])->name("laporan-produksi-isi");

                Route::get('/laporan-produksi/datas/{id}',[ProdukController::class,'detail_laporan_produksi'])->name('laporan-produksi-detail');
                Route::post('/laporan-produksi/datas-detail',[DatatableController::class,'detail_laporan_produksi_data'])->name('laporan-produksi-detail-data');
                Route::post('/laporan-produksi/data-details-lap',[ProdukController::Class,'get_detail_laporan_produksi'])->name('laporan-produksi-get-detail');
                Route::post('/laporan-produksi/data-details-updated',[ProdukController::Class,'update_detail_laporan_produksi'])->name('laporan-produksi-update-detail');
                Route::post('/laporan-produksi/data-get-totals',[ProdukController::class,'laporan_produksi_get_totals'])->name('lap-produk-totals');
                Route::post('/laporan-produksi/data-get-calculate',[ProdukController::class,'laporan_produksi_get_calculate'])->name('lap-produk-calculate');

            });
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
                Route::get('/cuti/data-hrd',[DatatableController::class,'data_cuti_hrd'])->name('data-cuti-hrd');
            });

            Route::middleware(['role:korlap:admin:hrd'])->group(function() {
                Route::get('/karyawan/data-admin',[DatatableController::class,'data_karyawan_admin'])->name('data-kr-admin');
            });

            Route::middleware(['role:direktur'])->group(function() {
                Route::get('/referensi-kerja-direktur/{nama_client}/{id}', function($c,$id) {
                    // dd($c,$id);
                    abort(500);
                })->name('direktur-rf-index');

                Route::get('/pre-order-direktur/{nama_client}/{id}', function($c,$id) {
                    // dd($c,$id);
                    abort(500);

                })->name('direktur-pre-order-index');
            });

            // Route::get('/pre-order/{nama_client}/referensi-kerja/{id}',[ReferensiKerjaController::class,'index_direktur'])->name('direktur-rf-index');
            Route::post('/check-tanda-tangan',[HomeController::class,'checking_tanda_tangan'])->name('check-tanda-tangan');
            Route::post('/check-tanda-tangan/save',[HomeController::class,'save_tanda_tangan'])->name('save-tanda-tangan');
        });

        Route::get("/profile/{menu}",[ProfileController::class,'index'])->name('profile');
        Route::get("/profile/data-pribadi",[ProfileController::class,'index_dataPribadi'])->name('profile-datadiri');
        Route::post("/profile/update-password",[ProfileController::class,'update_password'])->name('update_password');
        Route::post("/profile/upload-files",[ProfileController::class,'upload_files'])->name('files-uploads');
        Route::get('/profile/tes/filemanager',[DatatableController::class,'file_manager'])->name('profile-filemanager');
        Route::post('/profile/update-da',[ProfileController::class,'update'])->name('profile-update');

    });

});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CutiController;

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

Route::post('login', [LoginController::class, 'login']);

route::middleware(['auth'])->group(function() {
    route::get('/home', function() {
        return response()->json(Auth::user()->name);
    });
});

Route::prefix('cuti')->group(function() {
    Route::get('get-data-cuti',[CutiController::class,'get_data_cuti']);
    Route::post('create-cuti-internal',[CutiController::class,'create_cuti_internal']);
    Route::get('get-data-cuti-manager',[CutiController::class,'get_data_cuti_manager']);
    Route::get('get-data-cuti-hrd',[CutiController::class,'get_data_cuti_spv_hrd']);
    Route::get('get-data-cuti-dir-hrd',[CutiController::class,'get_data_cuti_dir_hrd']);
    Route::post('update',[CutiController::class,'update_status']);

});


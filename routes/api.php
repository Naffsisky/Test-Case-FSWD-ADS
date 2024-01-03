<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\CutiController;


Route::get('karyawan/old-employee', [KaryawanController::class, 'oldEmployee']);
Route::get('karyawan/haved-cuties', [KaryawanController::class, 'havedCuties']);
Route::get('karyawan/remaining-leave-quota', [KaryawanController::class, 'remainingLeaveQuota']);
Route::get('karyawan/nomor-induk/{nomor_induk}', [KaryawanController::class, 'findByNomorInduk']);

Route::resource('cuti', CutiController::class);
Route::resource('karyawan', KaryawanController::class);

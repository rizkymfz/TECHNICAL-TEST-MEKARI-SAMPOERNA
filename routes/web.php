<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TopupController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\Auth\RegistersController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/registers', [RegistersController::class, 'showRegisterForm'])->name('registers.create');
Route::post('/registers/store', [RegistersController::class, 'register'])->name('registers.store');

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/history', [HomeController::class, 'history'])->name('dashboard.history');

Route::get('/topup/create', [TopupController::class, 'create'])->name('topup.create');
Route::post('/topup/store', [TopupController::class, 'store'])->name('topup.store');

Route::get('/transfer/create', [TransferController::class, 'create'])->name('transfer.create');
Route::post('/transfer/store', [TransferController::class, 'store'])->name('transfer.store');
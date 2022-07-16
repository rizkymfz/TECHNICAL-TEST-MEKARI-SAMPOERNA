<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\HistoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/', function () {
    return "TECHNICAL TEST MEKARI SAMPOERNA - Rizky Maulana Fauzi";
});
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('auth/me', [AuthController::class, 'authMe']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    Route::prefix('wallet')->group(function () {
        Route::post('/topup', [WalletController::class, 'topupWallet']);
        Route::post('/transfer', [WalletController::class, 'transferWallet']);
        Route::get('/history', [HistoryController::class, 'history']);
    });

});

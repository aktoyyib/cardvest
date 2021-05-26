<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\WithdrawalController;

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

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{transaction:reference}', [TransactionController::class, 'get']);
    Route::post('/transactions/sell', [TransactionController::class, 'sell']);
    Route::post('/transactions/buy', [TransactionController::class, 'buy']);

    // Withdrawals
    Route::get('/withdrawals', [WithdrawalController::class, 'index']);
    Route::get('/withdrawals/{withdrawal:reference}', [WithdrawalController::class, 'get']);
    Route::post('/withdrawals', [WithdrawalController::class, 'store']);

});

Route::fallback(function() {
    return response()->json([
        'message' => 'Page not Found. If error persists, contact info@cardvest.ng'
    ], 404);
});
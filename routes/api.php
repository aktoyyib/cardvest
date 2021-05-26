<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\WithdrawalController;
use App\Http\Controllers\API\CardController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\WalletController;

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

    // Giftcard Categories
    Route::post('/giftcard-categories', [CategoryController::class, 'create']); // 🔒
    Route::put('/giftcard-categories/{category}', [CategoryController::class, 'update']); // 🔒
    Route::delete('/giftcard-categories/{category}', [CategoryController::class, 'destroy']); // 🔒
    Route::get('/giftcard-categories', [CategoryController::class, 'index']);
    Route::get('/giftcard-categories/sell/{category}', [CategoryController::class, 'cardsUsersCanSell']);
    Route::get('/giftcard-categories/buy/{category}', [CategoryController::class, 'cardsUsersCanBuy']);

    // Giftcards
    Route::post('/giftcards', [CardController::class, 'create']); // 🔒
    Route::put('/giftcards/{card}', [CardController::class, 'update']); // 🔒
    Route::delete('/giftcards/{card}', [CardController::class, 'destroy']); // 🔒
    Route::get('/giftcards', [CardController::class, 'index']);
    Route::get('/giftcards/sell', [CardController::class, 'cardsUsersCanSell']);
    Route::get('/giftcards/buy', [CardController::class, 'cardsUsersCanBuy']);
    Route::get('/giftcards/{card}', [CardController::class, 'show']);

    // Bank Accounts
    Route::get('/bank-accounts', [WalletController::class, 'getBankAccounts']);
    Route::post('/bank-accounts', [WalletController::class, 'addBankAccounts']);
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Page not Found. If error persists, contact info@cardvest.ng'
    ], 404);
});
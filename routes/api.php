<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\WithdrawalController;
use App\Http\Controllers\API\CardController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WalletController;
use App\Http\Controllers\API\ReferralController;

use App\Http\Controllers\Admin\PushNotificationController;

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

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'create']);
Route::get('/payment', function () {
    return response()->json([
        'data' => request()->all()
    ]);
})->name('payment-callback');
Route::post('/webhook/flutterwave', [TransactionController::class, 'webhook'])->name('webhook');

Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::get('/auth/user', [AuthController::class, 'user']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Users
    Route::get('/users/me', [UserController::class, 'me']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::put('/users/{user}/password', [UserController::class, 'updatePassword']);

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/payouts', [TransactionController::class, 'payouts']);
    Route::post('/transactions/image-upload', [TransactionController::class, 'upload']);
    Route::post('/transactions/sell', [TransactionController::class, 'sell']);
    Route::post('/transactions/buy', [TransactionController::class, 'buy']);
    Route::get('/transactions/{transaction:reference}', [TransactionController::class, 'get']);
    Route::delete('/transactions/{transaction:reference}', [TransactionController::class, 'destroy']);

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
    Route::get('/bank-accounts/banks', [WalletController::class, 'banks']);
    Route::post('/bank-accounts/verify', [WalletController::class, 'verify']);
    Route::post('/bank-accounts', [WalletController::class, 'addBankAccounts']);
    Route::delete('/bank-accounts/{bank}', [WalletController::class, 'destroy']);

    // Referrals
    Route::get('/referrals', [ReferralController::class, 'index']);

    // Push Notification's Route
    Route::post('/push-notification/register', [PushNotificationController::class, 'storeToken']);
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Page not Found. If error persists, contact info@cardvest.ng'
    ], 404);
});